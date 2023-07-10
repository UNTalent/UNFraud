<?php
namespace App\Service;

use App\Entity\Domain;

class InvestigationService {

    public function __construct(private DNSService $dnsService)
    {
    }

    public function newDomain(Domain $domain): void
    {
        if($domain->getReportCount() < 2){
            $this->refreshDataIfRequired($domain);
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



}
