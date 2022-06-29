<?php

namespace App\Entity;

use App\Entity\Traits\UUIDTrait;
use App\Repository\AnalysisRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnalysisRepository::class)]
class Analysis
{

    use UUIDTrait;

    #[ORM\ManyToOne(targetEntity: Rating::class, inversedBy: 'analyses')]
    #[ORM\JoinColumn(nullable: false)]
    private $rating;

    #[ORM\OneToMany(mappedBy: 'analysis', targetEntity: Domain::class)]
    private $domains;

    #[ORM\Column(type: 'string', length: 100)]
    private $title;

    #[ORM\Column(type: 'text', nullable: true)]
    private $instructions;

    public function __construct()
    {
        $this->domains = new ArrayCollection();
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
}
