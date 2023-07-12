<?php

namespace App\Entity;

use App\Entity\DomainData\DomainDnsRecord;
use App\Entity\Traits\UUIDTrait;
use App\Repository\DomainRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

#[ORM\Entity(repositoryClass: DomainRepository::class)]
#[ExclusionPolicy("ALL")]
class Domain
{

    use UUIDTrait;

    #[ORM\Column(type: 'string', length: 100, unique: true)]
    #[Expose]
    private $host;

    #[ORM\OneToMany(mappedBy: 'domain', targetEntity: Report::class)]
    private $reports;

    #[ORM\ManyToOne(targetEntity: Analysis::class, inversedBy: 'domains')]
    #[Expose]
    private $analysis;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $soaNameRecord = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $lastCheckAt = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $hostIpAddress = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $whoisData = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $whoisCreationDate = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $whoisExpirationDate = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $whoisUpdateDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $whoisOwner = null;

    #[ORM\Column(options: ['default' => 0])]
    private int $reportCount = 0;

    #[ORM\OneToMany(mappedBy: 'domain', targetEntity: DomainDnsRecord::class)]
    private Collection $domainDnsRecords;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'subdomains')]
    private ?self $parentDomain = null;

    #[ORM\OneToMany(mappedBy: 'parentDomain', targetEntity: self::class)]
    private Collection $subdomains;

    public function __construct($host)
    {
        $this->setHost($host);
        $this->reports = new ArrayCollection();
        $this->domainDnsRecords = new ArrayCollection();
        $this->subdomains = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getHost();
    }

    public function getHost(): ?string
    {
        return $this->host;
    }

    public function setHost(string $host): self
    {
        $this->host = $host;

        return $this;
    }

    /**
     * @return Collection<int, Report>
     */
    public function getReports(): Collection
    {
        return $this->reports;
    }

    public function addReport(Report $report): self
    {
        if (!$this->reports->contains($report)) {
            $this->reports[] = $report;
            $report->setDomain($this);
        }

        return $this;
    }

    public function removeReport(Report $report): self
    {
        if ($this->reports->removeElement($report)) {
            // set the owning side to null (unless already changed)
            if ($report->getDomain() === $this) {
                $report->setDomain(null);
            }
        }

        return $this;
    }

    public function getAnalysis(): ?Analysis
    {
        return $this->analysis;
    }

    public function setAnalysis(?Analysis $analysis): self
    {
        $this->analysis = $analysis;

        return $this;
    }

    public function getSoaNameRecord(): ?string
    {
        return $this->soaNameRecord;
    }

    public function setSoaNameRecord(?string $soaNameRecord): static
    {
        $this->soaNameRecord = $soaNameRecord;

        return $this;
    }

    public function getLastCheckAt(): ?\DateTimeImmutable
    {
        return $this->lastCheckAt;
    }

    public function setLastCheckAt(?\DateTimeImmutable $lastCheckAt): static
    {
        $this->lastCheckAt = $lastCheckAt;

        return $this;
    }

    public function getHostIpAddress(): ?string
    {
        return $this->hostIpAddress;
    }

    public function setHostIpAddress(?string $hostIpAddress): static
    {
        $this->hostIpAddress = $hostIpAddress;

        return $this;
    }

    public function getWhoisData(): ?string
    {
        return $this->whoisData;
    }

    public function setWhoisData(?string $whoisData): static
    {
        $this->whoisData = $whoisData;

        return $this;
    }

    public function getWhoisCreationDate(): ?\DateTimeImmutable
    {
        return $this->whoisCreationDate;
    }

    public function setWhoisCreationDate(?\DateTimeImmutable $whoisCreationDate): static
    {
        $this->whoisCreationDate = $whoisCreationDate;

        return $this;
    }

    public function getWhoisExpirationDate(): ?\DateTimeImmutable
    {
        return $this->whoisExpirationDate;
    }

    public function setWhoisExpirationDate(?\DateTimeImmutable $whoisExpirationDate): static
    {
        $this->whoisExpirationDate = $whoisExpirationDate;

        return $this;
    }

    public function getWhoisUpdateDate(): ?\DateTimeImmutable
    {
        return $this->whoisUpdateDate;
    }

    public function setWhoisUpdateDate(?\DateTimeImmutable $whoisUpdateDate): static
    {
        $this->whoisUpdateDate = $whoisUpdateDate;

        return $this;
    }

    public function getWhoisOwner(): ?string
    {
        return $this->whoisOwner;
    }

    public function setWhoisOwner(?string $whoisOwner): static
    {
        $this->whoisOwner = $whoisOwner;

        return $this;
    }

    public function getReportCount(): ?int
    {
        return $this->reportCount;
    }

    public function increaseReportCount(): static
    {
        $this->reportCount++;

        return $this;
    }

    /**
     * @return Collection<int, DomainDnsRecord>
     */
    public function getDomainDnsRecords(): Collection
    {
        return $this->domainDnsRecords;
    }


    /**
     * @return Collection<int, DnsRecord>
     */
    public function getDnsRecords(): Collection {
        return $this->getDomainDnsRecords()->map(function(DomainDnsRecord $record){
            return $record->getDnsRecord();
        });
    }

    public function addDomainDnsRecord(DomainDnsRecord $domainDnsRecord): static
    {
        if (!$this->domainDnsRecords->contains($domainDnsRecord)) {
            $this->domainDnsRecords->add($domainDnsRecord);
            $domainDnsRecord->setDomain($this);
        }

        return $this;
    }

    public function removeDomainDnsRecord(DomainDnsRecord $domainDnsRecord): static
    {
        if ($this->domainDnsRecords->removeElement($domainDnsRecord)) {
            // set the owning side to null (unless already changed)
            if ($domainDnsRecord->getDomain() === $this) {
                $domainDnsRecord->setDomain(null);
            }
        }

        return $this;
    }

    public function getParentDomain(): ?self
    {
        return $this->parentDomain;
    }

    public function setParentDomain(?self $parentDomain): static
    {
        $this->parentDomain = $parentDomain;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getSubdomains(): Collection
    {
        return $this->subdomains;
    }

    public function addSubdomain(self $subdomain): static
    {
        if (!$this->subdomains->contains($subdomain)) {
            $this->subdomains->add($subdomain);
            $subdomain->setParentDomain($this);
        }

        return $this;
    }

    public function removeSubdomain(self $subdomain): static
    {
        if ($this->subdomains->removeElement($subdomain)) {
            // set the owning side to null (unless already changed)
            if ($subdomain->getParentDomain() === $this) {
                $subdomain->setParentDomain(null);
            }
        }

        return $this;
    }

    public function isDangerous(): ?bool
    {
        return $this->getAnalysis()?->getRating()?->isDangerous();
    }

    public function isSafe(): ?bool
    {
        return $this->isDangerous() === false;
    }
}
