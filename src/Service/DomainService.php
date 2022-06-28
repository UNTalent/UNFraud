<?php
namespace App\Service;

use App\Entity\Domain;
use App\Entity\Report;
use App\Repository\DomainRepository;
use App\Repository\ReportRepository;

class DomainService {

    public function __construct(private DomainRepository $domainRepository, private ReportRepository $reportRepository)
    {
    }

    public function getReport($text): ?Report {
        $domain = $this->extractDomain($text);
        $domain = $this->domainRepository->findSimilar($domain);

        $report = new Report($text, $domain);
        $this->reportRepository->add($report);

        return $report;
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
        $domain = mb_strtolower($domain);
        if(preg_match('#^www\.#i', $domain)){
            $domain = mb_substr($domain, 4);
        }
        return new Domain($domain);
    }

}
