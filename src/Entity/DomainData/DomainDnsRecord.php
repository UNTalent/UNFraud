<?php

namespace App\Entity\DomainData;

use App\Entity\Domain;
use App\Entity\Traits\UUIDTrait;
use App\Repository\DomainData\DomainDnsRecordRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DomainDnsRecordRepository::class)]
#[ORM\Table(name: 'domain_data__dns_records__domains')]
class DomainDnsRecord
{
    use UUIDTrait;

    #[ORM\ManyToOne(inversedBy: 'domainDnsRecords')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Domain $domain = null;

    #[ORM\ManyToOne(inversedBy: 'domainDnsRecords')]
    #[ORM\JoinColumn(nullable: false)]
    private ?DnsRecord $dnsRecord = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $lastSeenAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $firstSeenAt = null;

    public function __construct(DnsRecord $dnsRecord, Domain $domain)
    {
        $this->setDnsRecord($dnsRecord);
        $this->setDomain($domain);
        $this->setLastSeenAt(new \DateTimeImmutable());
        $this->setFirstSeenAt(new \DateTimeImmutable());
    }

    public function getDomain(): ?Domain
    {
        return $this->domain;
    }

    private function setDomain(?Domain $domain): static
    {
        $this->domain = $domain;

        return $this;
    }

    public function getDnsRecord(): ?DnsRecord
    {
        return $this->dnsRecord;
    }

    private function setDnsRecord(?DnsRecord $dnsRecord): static
    {
        $this->dnsRecord = $dnsRecord;

        return $this;
    }

    public function getLastSeenAt(): ?\DateTimeImmutable
    {
        return $this->lastSeenAt;
    }

    public function setLastSeenAt(\DateTimeImmutable $lastSeenAt): static
    {
        $this->lastSeenAt = $lastSeenAt;

        return $this;
    }

    public function getFirstSeenAt(): ?\DateTimeImmutable
    {
        return $this->firstSeenAt;
    }

    private function setFirstSeenAt(\DateTimeImmutable $firstSeenAt): static
    {
        $this->firstSeenAt = $firstSeenAt;

        return $this;
    }
}
