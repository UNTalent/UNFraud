<?php

namespace App\Entity;

use App\Entity\DomainData\DnsRecord;
use App\Entity\Traits\UUIDTrait;
use App\Repository\AnalysisRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

#[ORM\Entity(repositoryClass: AnalysisRepository::class)]
#[ExclusionPolicy("ALL")]
class Analysis
{

    use UUIDTrait;

    #[ORM\ManyToOne(targetEntity: Rating::class, inversedBy: 'analyses')]
    #[ORM\JoinColumn(nullable: false)]
    #[Expose]
    private $rating;

    #[ORM\OneToMany(mappedBy: 'analysis', targetEntity: Domain::class)]
    private $domains;

    #[ORM\Column(type: 'string', length: 100)]
    #[Expose]
    private $title;

    #[ORM\Column(type: 'text', nullable: true)]
    private $instructions;

    #[ORM\OneToMany(mappedBy: 'applyAnalysis', targetEntity: DnsRecord::class)]
    private Collection $associatedDnsRecords;

    #[ORM\Column(options: ["default" => 0])]
    private int $recommendationWeight = 0;

    public function __toString(): string
    {
        return $this->getTitle();
    }

    public function __construct()
    {
        $this->domains = new ArrayCollection();
        $this->associatedDnsRecords = new ArrayCollection();
    }

    public function getRating(): ?Rating
    {
        return $this->rating;
    }

    public function setRating(?Rating $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * @return Collection<int, Domain>
     */
    public function getDomains(): Collection
    {
        return $this->domains;
    }

    public function addDomain(Domain $domain): self
    {
        if (!$this->domains->contains($domain)) {
            $this->domains[] = $domain;
            $domain->setAnalysis($this);
        }

        return $this;
    }

    public function removeDomain(Domain $domain): self
    {
        if ($this->domains->removeElement($domain)) {
            // set the owning side to null (unless already changed)
            if ($domain->getAnalysis() === $this) {
                $domain->setAnalysis(null);
            }
        }

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getInstructions(): ?string
    {
        return $this->instructions;
    }

    public function setInstructions(?string $instructions): self
    {
        $this->instructions = $instructions;

        return $this;
    }

    /**
     * @return Collection<int, DnsRecord>
     */
    public function getAssociatedDnsRecords(): Collection
    {
        return $this->associatedDnsRecords;
    }

    public function addAssociatedDnsRecord(DnsRecord $associatedDnsRecord): static
    {
        if (!$this->associatedDnsRecords->contains($associatedDnsRecord)) {
            $this->associatedDnsRecords->add($associatedDnsRecord);
            $associatedDnsRecord->setApplyAnalysis($this);
        }

        return $this;
    }

    public function removeAssociatedDnsRecord(DnsRecord $associatedDnsRecord): static
    {
        if ($this->associatedDnsRecords->removeElement($associatedDnsRecord)) {
            // set the owning side to null (unless already changed)
            if ($associatedDnsRecord->getApplyAnalysis() === $this) {
                $associatedDnsRecord->setApplyAnalysis(null);
            }
        }

        return $this;
    }

    public function getRecommendationWeight(): int
    {
        return $this->recommendationWeight;
    }

    public function setRecommendationWeight(int $recommendationWeight): static
    {
        $this->recommendationWeight = $recommendationWeight;

        return $this;
    }
}
