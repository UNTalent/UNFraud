<?php
namespace App\Service;

use App\Entity\Domain;
use App\Entity\DomainData\DnsRecord;
use App\Entity\DomainData\DomainDnsRecord;
use App\Repository\DomainData\DnsRecordRepository;
use App\Repository\DomainData\DomainDnsRecordRepository;

class DNSService {

    private $existingRecords = [];

    public function __construct(private DnsRecordRepository $dnsRecordRepository,
                                private DomainDnsRecordRepository $domainDnsRecordRepository)
    {
    }

    public function saveDNS(Domain $domain): bool
    {

        if(! $this->saveDNSEntries($domain, [DNS_A]))
            return false;

        if(! $this->saveDNSEntries($domain, [DNS_CNAME, DNS_MX, DNS_NS, DNS_SOA]))
            return false;

        return true;
    }

    private function saveDNSEntries(Domain $domain, array $types): bool {
        $count = 0;
        foreach ($types as $type) {
            if($this->saveDNSEntriesOfType($domain, $type))
                $count++;
        }
        return $count > 0;
    }

    private function saveDNSEntriesOfType(Domain $domain, int $type): bool {

        try {
            $dns = dns_get_record($domain->getHost(), $type);
        } catch (\Exception $e) {
            return false;
        }

        if(!$dns){
            return false;
        }

        foreach ($dns as $value) {
            $this->saveDNSEntry($domain, $value);
        }

        return true;
    }

    private function saveDNSEntry(Domain $domain, array $entry): void
    {
        $type = $entry['type'] ?? null;
        $value = $this->getDNSValue($type, $entry);

        $record = $this->getDnsRecord($type, $value);
        $domainRecord = $this->saveDomainDnsRecord($record, $domain);
        $domainRecord->setLastSeenAt(new \DateTimeImmutable('now'));

    }

    private function getDNSValue(string $type, array $entry): ?string {

        switch ($type){
            case 'A':
                return $entry['ip'] ?? null;
            case 'CNAME':
                return $entry['target'] ?? null;
            case 'NS':
                return $entry['target'] ?? null ;
            case 'SOA':
                return $entry['rname'] ?? null ;
            case 'MX':
                return $entry['target'] ?? null ;
            default:
                throw new \LogicException("Unknown DNS type: $type");
        }
    }

    private function getDnsRecord(string $type, string $value): ?DnsRecord
    {

        $key = $type . $value;
        $record = $this->existingRecords[$key] ?? null;
        if($record){
            return $record;
        }

        $newRecord = new DnsRecord($type, $value);
        $record = $this->dnsRecordRepository->findSimilar($newRecord);
        if(!$record){
            $record = $newRecord;
            $this->dnsRecordRepository->save($record);
        }

        $this->existingRecords[$key] = $record;

        return $record;
    }

    private function saveDomainDnsRecord(DnsRecord $dnsRecord, Domain $domain){

        $domainRecord = $this->domainDnsRecordRepository->findOneByRecordAndDomain($dnsRecord, $domain);
        if(!$domainRecord){
            $domainRecord = new DomainDnsRecord($dnsRecord, $domain);
            $this->domainDnsRecordRepository->save($domainRecord);
        }
        return $domainRecord;
    }


}
