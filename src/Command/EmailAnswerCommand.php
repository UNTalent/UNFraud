<?php

namespace App\Command;

use App\Entity\Domain;
use App\Service\DomainService;
use PhpImap\Mailbox;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Webklex\PHPIMAP\Client;
use Webklex\PHPIMAP\ClientManager;
use Webklex\PHPIMAP\Message;

#[AsCommand(
    name: 'app:email:answer',
    description: 'Add a short description for your command',
)]
class EmailAnswerCommand extends Command
{

    public function __construct(private DomainService $domainService)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $client = $this->getMailClient();
        $client->connect();


        $client->getFolder('INBOX')->query()->unseen()->leaveUnread()->get()->each(function ($message) {
            $this->treatMessage($message);
        });


        $io->success('Emails treated successfully.');

        return Command::SUCCESS;
    }

    private function treatMessage(Message $message): bool {

        if($message->hasFlag('SEEN')){
            return false;
        }

        // $message->setFlag('SEEN'); // TODO: uncomment this line to mark the message as read


        $reply = $this->getReply($message);
        if(! $reply)
            return false;

        //$message->setFlag('AutoReport');
        //$message->move('INBOX/AutoReport');
        //$message->reply()->setBody($reply);
        dd($reply);

        $emailId = $message->getMessageId()->toString();
        dd($emailId);
        return true;

    }

    private function getMailClient(): Client {
        return (new ClientManager())->make([
            'validate_cert' => true,
            'host'          => $_ENV['IMAP_HOST'],
            'port'          => $_ENV['IMAP_PORT'],
            'encryption'    => $_ENV['IMAP_ENCRYPTION'],
            'username'      => $_ENV['IMAP_USERNAME'],
            'password'      => $_ENV['IMAP_PASSWORD'],
            'protocol'      => $_ENV['IMAP_PROTOCOL']
        ]);
    }

    private function getReply(Message $message): string|false {
        $body = $message->getHTMLBody();
        if (empty($body)) {
            $body = $message->getTextBody();
        }

        $senders = $this->extractSenders($message);

        $domains = $this->getAllDomainsInText($body, $senders);
        if(! count($domains))
            return false;

        $reply = "Here is the report based on the emails we were able to extract:\n\n";
        foreach ($domains as $domain) {
            $reply .= "* " . $this->getDomainReplyRow($domain) . "\n";
        }
        $reply .= "\nDo not reply to this email, it is an automated report.";

        return $reply;
    }

    private function getDomainReplyRow(Domain $domain): string {
        if(! $analysis = $domain->getAnalysis())
            return "$domain not analysed yet";

        $rating = $analysis->getRating();

        return "$domain: $rating ($analysis)";
    }

    private function extractSenders(Message $message): array {
        $d = [];
        foreach ($message->getFrom()->all() as $sender) {
            $email = $sender->mail;
            if($email)
                $d[] = $email;
        }
        return $d;
    }

    private function getAllDomainsInText(string $content, array $toExclude): array {
        $pattern = '/[a-z0-9_\-\+\.]+@[a-z0-9\-]+\.([a-z]{2,4})(?:\.[a-z]{2})?/i';
        preg_match_all($pattern, $content, $matches);
        $emails =  $matches[0] ?? [];

        $domains = [];
        foreach ($emails as $emailAddress) {
            if(in_array($emailAddress, $toExclude))
                continue;

            $report = $this->domainService->getReport($emailAddress);
            if ($report) {
                $domains[] = $report->getDomain();
            }
        }

        /** @var Domain[] $found */
        $domains = array_unique($domains);
        usort($domains, function (Domain $a, Domain $b) {

            if(! $a->getAnalysis() && ! $b->getAnalysis())
                return 0;

            if(! $b->getAnalysis())
                return -1;

            if(!$a->getAnalysis() )
                return 1;

            return $a->getAnalysis()->getScore() < $b->getAnalysis()->getScore() ? -1 : 1;
        });

        return $domains;
    }
}
