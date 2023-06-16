<?php

namespace App\Command;

use App\Entity\Domain;
use App\Repository\DomainRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:investigate:domain',
    description: 'Investigate a domain name based on its DNS information',
)]
class InvestigateDomainCommand extends Command
{

    public function __construct(private DomainRepository $domainRepository, private EntityManagerInterface $em)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $domains = $this->domainRepository->findToInvestigate();

        foreach ($domains as $domain) {
            $io->writeln("Checking $domain");
            $name = $this->investigate($domain);

            if ($name) {
                $io->success("Found $name for $domain");
            } else {
                $io->writeln("Not found");
            }

        }

        $this->em->flush();
        $io->success('Done');

        return Command::SUCCESS;
    }

    private function investigate(Domain $domain): false|string|null
    {

        $domain->setLastCheckAt(new \DateTimeImmutable('now'));

        $host = $domain->getHost();

        $dns = dns_get_record($host, DNS_SOA);

        foreach ($dns as $record) {
            if ($rname = $record['rname'] ?? false) {
                $domain->setSoaNameRecord($rname);
            }
        }

        return true;
    }
}
