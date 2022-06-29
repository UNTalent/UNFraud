<?php

namespace App\Entity;

use App\Entity\Traits\UUIDTrait;
use App\Repository\DomainRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DomainRepository::class)]
class Domain
{

    use UUIDTrait;

    #[ORM\Column(type: 'string', length: 100, unique: true)]
    private $host;

    #[ORM\OneToMany(mappedBy: 'domain', targetEntity: Report::class)]
    private $reports;

    #[ORM\ManyToOne(targetEntity: Analysis::class, inversedBy: 'domains')]
    private $analysis;

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
}
