<?php

namespace App\Entity\Complaint;

use App\Entity\Traits\UUIDTrait;
use App\Repository\Complaint\ComplaintStatusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ComplaintStatusRepository::class)]
#[ORM\Table(name: 'complaint_status')]
class ComplaintStatus
{
    use UUIDTrait;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column]
    private ?bool $hasReplied = null;

    #[ORM\Column]
    private ?bool $hasSentSensitiveData = null;

    #[ORM\Column]
    private ?bool $hasSentMoney = null;

    #[ORM\OneToMany(mappedBy: 'status', targetEntity: Complaint::class)]
    private Collection $complaints;

    public function __toString(): string
    {
        return $this->getName() ?? '';
    }

    public function __construct()
    {
        $this->complaints = new ArrayCollection();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function isHasReplied(): ?bool
    {
        return $this->hasReplied;
    }

    public function setHasReplied(bool $hasReplied): static
    {
        $this->hasReplied = $hasReplied;

        return $this;
    }

    public function isHasSentSensitiveData(): ?bool
    {
        return $this->hasSentSensitiveData;
    }

    public function setHasSentSensitiveData(bool $hasSentSensitiveData): static
    {
        $this->hasSentSensitiveData = $hasSentSensitiveData;

        return $this;
    }

    public function isHasSentMoney(): ?bool
    {
        return $this->hasSentMoney;
    }

    public function setHasSentMoney(bool $hasSentMoney): static
    {
        $this->hasSentMoney = $hasSentMoney;

        return $this;
    }

    /**
     * @return Collection<int, Complaint>
     */
    public function getComplaints(): Collection
    {
        return $this->complaints;
    }

    public function addComplaint(Complaint $complaint): static
    {
        if (!$this->complaints->contains($complaint)) {
            $this->complaints->add($complaint);
            $complaint->setStatus($this);
        }

        return $this;
    }

    public function removeComplaint(Complaint $complaint): static
    {
        if ($this->complaints->removeElement($complaint)) {
            // set the owning side to null (unless already changed)
            if ($complaint->getStatus() === $this) {
                $complaint->setStatus(null);
            }
        }

        return $this;
    }
}
