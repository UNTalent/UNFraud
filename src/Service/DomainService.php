<?php
namespace App\Service;

use App\Entity\Domain;
use App\Repository\DomainRepository;

class DomainService {

    public function __construct(private DomainRepository $domainRepository)
    {
    }

    public function getDomain($domain): ?Domain {
        $domain = $this->extractDomain($domain);
        $this->domainRepository->findSimilar($domain);
        return $domain;
    }

    private function extractDomain(?string $text): ?Domain {

        if(! $text) return null;

        if(filter_var($text, FILTER_VALIDATE_EMAIL ) ) {
            $email_parts = explode('@', $text);
            $domain = array_pop($email_parts);
            return $this->createDomain($domain);
        }

        $parse = parse_url($text);
        $domain = $parse['host'] ?? null;
        if($domain){
            return $this->createDomain($domain);
        }

        return null;
    }

    private function createDomain(string $domain){
        return new Domain($domain);
    }

}
