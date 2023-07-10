<?php

namespace App\Command;

use App\Repository\DomainRepository;
use App\Service\DNSService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:investigate:dns',
    description: 'Save DNS values for each domain',
)]
class InvestigateDnsCommand extends Command
{

    public function __construct(private DomainRepository $domainRepository, private DNSService $DNSService,
                                private EntityManagerInterface $em)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title("DNS collection");

        $domains = $this->domainRepository->findAll();
        $progress = $io->createProgressBar(count($domains));
        foreach ($domains as $domain){
            sleep(1);

            $io->newLine(3);
            $io->block("Domain: $domain");
            $progress->advance();

            $this->DNSService->saveDNS($domain);
            $this->em->flush();
        }
        $progress->finish();

        return Command::SUCCESS;
    }
}
