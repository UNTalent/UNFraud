<?php

namespace App\Entity;

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

    public function __construct($host)
    {
        $this->setHost($host);
        $this->reports = new ArrayCollection();
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
}
