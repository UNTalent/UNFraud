<?php

namespace App\Entity\Complaint;

use App\Entity\Traits\UUIDTrait;
use App\Repository\Complaint\ComplaintRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Country;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\PositiveOrZero;

#[ORM\Entity(repositoryClass: ComplaintRepository::class)]
#[ORM\Table(name: 'complaint')]
class Complaint
{
    use UUIDTrait;

    #[ORM\Column(length: 200)]
    #[Email]
    private ?string $email = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[PositiveOrZero]
    private ?float $moneySent = null;

    #[ORM\Column(length: 100)]
    #[Country]
    private ?string $country = null;

    #[ORM\Column(length: 200)]
    private ?string $code = null;

    #[ORM\ManyToOne(inversedBy: 'complaints')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ComplaintStatus $status = null;

    #[ORM\OneToMany(mappedBy: 'complaint', targetEntity: ComplaintReport::class)]
    private Collection $complaintReports;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTimeImmutable('now'));
        $this->resetCode();
        $this->complaintReports = new ArrayCollection();
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getMoneySent(): ?float
    {
        return $this->moneySent;
    }

    public function setMoneySent(?float $moneySent): static
    {
        $this->moneySent = $moneySent;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function resetCode(): static
    {

        $this->code = bin2hex(random_bytes(16));

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    private function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getStatus(): ?ComplaintStatus
    {
        return $this->status;
    }

    public function setStatus(?ComplaintStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, ComplaintReport>
     */
    public function getComplaintReports(): Collection
    {
        return $this->complaintReports;
    }

    public function addComplaintReport(ComplaintReport $complaintReport): static
    {
        if (!$this->complaintReports->contains($complaintReport)) {
            $this->complaintReports->add($complaintReport);
            $complaintReport->setComplaint($this);
        }

        return $this;
    }

    public function removeComplaintReport(ComplaintReport $complaintReport): static
    {
        if ($this->complaintReports->removeElement($complaintReport)) {
            // set the owning side to null (unless already changed)
            if ($complaintReport->getComplaint() === $this) {
                $complaintReport->setComplaint(null);
            }
        }

        return $this;
    }

    public function getReports(): Collection
    {
        return $this->complaintReports->map(fn($complaintReport) => $complaintReport->getReport());
    }
}
