<?php

namespace App\Entity\Complaint;

use App\Entity\Report;
use App\Entity\Traits\UUIDTrait;
use App\Repository\Complaint\ComplaintReportRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ComplaintReportRepository::class)]
class ComplaintReport
{
    use UUIDTrait;

    #[ORM\ManyToOne(inversedBy: 'complaintReports', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Complaint $complaint = null;

    #[ORM\OneToOne(inversedBy: 'complaintReport', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Report $report = null;

    public function __construct(Complaint $complaint, Report $report)
    {
        $this->setComplaint($complaint);
        $this->setReport($report);
    }

    public function getComplaint(): ?Complaint
    {
        return $this->complaint;
    }

    public function setComplaint(?Complaint $complaint): static
    {
        $this->complaint = $complaint;

        return $this;
    }

    public function getReport(): ?Report
    {
        return $this->report;
    }

    public function setReport(Report $report): static
    {
        $this->report = $report;

        return $this;
    }
}
