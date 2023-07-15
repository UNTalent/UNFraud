<?php
namespace App\Service;

use App\Entity\Domain;
use App\Repository\DomainRepository;

class InvestigationService {

    public function __construct(private DNSService $dnsService, private DomainRepository $domainRepository)
    {
    }

    public function newDomain(Domain $domain): void
    {
        if($domain->getReportCount() < 2){
            $this->refreshDataIfRequired($domain);
            $this->tryToAttachToParent($domain);
        }
        $this->addAnalysis($domain);
    }

    public function refreshDataIfRequired(Domain $domain): void
    {
        $limit = new \DateTimeImmutable('-6 hours');
        if ($domain->getLastCheckAt() > $limit)
            return;

        $domain->setLastCheckAt(new \DateTimeImmutable('now'));

        $this->dnsService->saveDNS($domain);
        $this->addAnalysis($domain);
    }

    private function addAnalysis(Domain $domain): bool
    {
        if ($domain->getAnalysis())
            return true;

        foreach ($domain->getDnsRecords() as $dnsRecord){
            if($analysis = $dnsRecord->getApplyAnalysis()){
                $domain->setAnalysis($analysis);
                return true;
            }

        }

        return false;
    }

    private function tryToAttachToParent(Domain $domain): bool {
        $hostparts = explode('.', $domain->getHost());
        while(array_shift($hostparts)){
             if($this->addParent(implode('.', $hostparts), $domain)){
                 return true;
             }
        }
        return false;
    }

    private function addParent(string $hostname, Domain $domain): bool {
        $parent = $this->domainRepository->findOneByHost($hostname);
        if(! $parent)
            return false;

        $domain->setParentDomain($parent);
        return true;
    }



}
