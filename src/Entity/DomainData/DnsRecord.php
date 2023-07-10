<?php

namespace App\Entity\DomainData;

use App\Entity\Domain;
use App\Entity\Traits\UUIDTrait;
use App\Repository\DomainData\DnsRecordRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: DnsRecordRepository::class)]
#[ORM\Table(name: 'domain_data__dns_records')]
#[ORM\UniqueConstraint(
    name: 'unique_record',
    columns: ['record_type', 'value']
)]
class DnsRecord
{
    use UUIDTrait;

    #[ORM\Column(length: 20)]
    private ?string $recordType = null;

    #[ORM\Column(length: 255)]
    private ?string $value = null;

    #[ORM\OneToMany(mappedBy: 'dnsRecord', targetEntity: DomainDnsRecord::class)]
    private Collection $domainDnsRecords;

    public function __construct(string $recordType, string $value)
    {
        $this->setRecordType($recordType);
        $this->setValue($value);
        $this->domainDnsRecords = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getRecordType() . ' ' . $this->getValue();
    }

    public function getRecordType(): ?string
    {
        return $this->recordType;
    }

    private function setRecordType(string $recordType): static
    {
        $this->recordType = $recordType;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    private function setValue(string $value): static
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return Collection<int, DomainDnsRecord>
     */
    public function getDomainDnsRecords(): Collection
    {
        return $this->domainDnsRecords;
    }

    public function addDomainDnsRecord(DomainDnsRecord $domainDnsRecord): static
    {
        if (!$this->domainDnsRecords->contains($domainDnsRecord)) {
            $this->domainDnsRecords->add($domainDnsRecord);
            $domainDnsRecord->setDnsRecord($this);
        }

        return $this;
    }

    public function removeDomainDnsRecord(DomainDnsRecord $domainDnsRecord): static
    {
        if ($this->domainDnsRecords->removeElement($domainDnsRecord)) {
            // set the owning side to null (unless already changed)
            if ($domainDnsRecord->getDnsRecord() === $this) {
                $domainDnsRecord->setDnsRecord(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Domain>
     */
    public function getDomains(): Collection {
        return $this->getDomainDnsRecords()->map(fn(DomainDnsRecord $domainDnsRecord) => $domainDnsRecord->getDomain());
    }
}
