<?php
namespace App\Service;

use App\Entity\Domain;

class InvestigationService {

    public function __construct(private DNSService $dnsService)
    {
    }

    public function newDomain(Domain $domain): void
    {
        $this->dnsService->saveDNS($domain);
    }



}
