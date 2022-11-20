<?php

namespace App\Entity;

use App\Entity\Traits\UUIDTrait;
use App\Repository\RatingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RatingRepository::class)]
class Rating
{
    use UUIDTrait;

    #[ORM\Column(type: 'string', length: 30)]
    private $name;

    #[ORM\Column(type: 'text', nullable: true)]
    private $description;

    #[ORM\Column(type: 'text', nullable: true)]
    private $toDo;

    #[ORM\Column(type: 'string', length: 50)]
    private $cssClass;

    #[ORM\OneToMany(mappedBy: 'rating', targetEntity: Analysis::class)]
    private $analyses;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $isDangerous = null;

    public function __construct()
    {
        $this->analyses = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getName();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getToDo(): ?string
    {
        return $this->toDo;
    }

    public function setToDo(?string $toDo): self
    {
        $this->toDo = $toDo;

        return $this;
    }

    public function getCssClass(): ?string
    {
        return $this->cssClass;
    }

    public function setCssClass(string $cssClass): self
    {
        $this->cssClass = $cssClass;

        return $this;
    }

    /**
     * @return Collection<int, Analysis>
     */
    public function getAnalyses(): Collection
    {
        return $this->analyses;
    }

    public function addAnalysis(Analysis $analysis): self
    {
        if (!$this->analyses->contains($analysis)) {
            $this->analyses[] = $analysis;
            $analysis->setRating($this);
        }

        return $this;
    }

    public function removeAnalysis(Analysis $analysis): self
    {
        if ($this->analyses->removeElement($analysis)) {
            // set the owning side to null (unless already changed)
            if ($analysis->getRating() === $this) {
                $analysis->setRating(null);
            }
        }

        return $this;
    }

    public function isDangerous(): ?bool
    {
        return $this->isDangerous;
    }

    public function setIsDangerous(?bool $isDangerous): self
    {
        $this->isDangerous = $isDangerous;

        return $this;
    }
}
