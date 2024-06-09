<?php

namespace App\Entity;

use App\Entity\Complaint\Complaint;
use App\Entity\Complaint\ComplaintReport;
use App\Entity\Traits\UUIDTrait;
use App\Repository\ReportRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\VirtualProperty;

#[ORM\Entity(repositoryClass: ReportRepository::class)]
#[ExclusionPolicy("ALL")]
class Report
{
    use UUIDTrait;

    #[ORM\Column(type: 'string', length: 200)]
    private $value;

    #[ORM\ManyToOne(targetEntity: Domain::class, cascade: ['persist'], inversedBy: 'reports')]
    #[ORM\JoinColumn(nullable: false)]
    #[Expose]
    private $domain;

    #[ORM\Column(type: 'datetime_immutable')]
    private $createdAt;

    #[ORM\OneToOne(mappedBy: 'report', cascade: ['persist', 'remove'])]
    private ?ComplaintReport $complaintReport = null;

    public function __construct($value, $domain)
    {
        $this
            ->setDomain($domain)
            ->setValue($value)
            ->setCreatedAt(new \DateTimeImmutable('now'))
        ;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getDomain(): ?Domain
    {
        return $this->domain;
    }

    public function setDomain(?Domain $domain): self
    {
        $this->domain = $domain;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    private function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    #[VirtualProperty]
    public function isAnalysed(): bool
    {
        return !!$this->getDomain()->getAnalysis();
    }

    public function getComplaintReport(): ?ComplaintReport
    {
        return $this->complaintReport;
    }

    public function setComplaintReport(ComplaintReport $complaintReport): static
    {
        // set the owning side of the relation if necessary
        if ($complaintReport->getReport() !== $this) {
            $complaintReport->setReport($this);
        }

        $this->complaintReport = $complaintReport;

        return $this;
    }

    public function getComplaint(): ?Complaint
    {
        return $this->getComplaintReport()?->getComplaint();
    }

    public function isDangerous(): ?bool
    {
        return $this->getDomain()->isDangerous();
    }

    public function isSafe(): ?bool
    {
        return $this->getDomain()->isSafe();
    }
}
