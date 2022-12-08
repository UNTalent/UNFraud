<?php

namespace App\Entity;

use App\Entity\Traits\UUIDTrait;
use App\Repository\RatingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

#[ORM\Entity(repositoryClass: RatingRepository::class)]
#[ExclusionPolicy("ALL")]
class Rating
{
    use UUIDTrait;

    #[ORM\Column(type: 'string', length: 30)]
    #[Expose]
    private $title;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Expose]
    private $description;

    #[ORM\Column(type: 'text', nullable: true)]
    private $toDo;

    #[ORM\Column(type: 'string', length: 50)]
    private $cssClass;

    #[ORM\OneToMany(mappedBy: 'rating', targetEntity: Analysis::class)]
    private $analyses;

    #[ORM\Column(type: 'boolean', nullable: true)]
    #[Expose]
    private $isDangerous = null;

    #[ORM\Column(type: 'float', options: ["default" => 0])]
    #[Expose]
    private $level = 0.0;

    public function __construct()
    {
        $this->analyses = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getTitle();
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

    private function getColor($light): string
    {
        $red = 0;
        $green = 120;
        $angle = ($green - $red) * $this->getLevel() + $red;

        return "hsl({$angle}deg 100% {$light}%)";
    }


    public function getBadgeColor(): string
    {
        return $this->getColor(35);
    }

    public function getBackgroundColor(): string
    {
        return $this->getColor(20);
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

    public function getLevel(): ?float
    {
        return $this->level;
    }

    public function setLevel(float $level): self
    {
        $this->level = $level;

        return $this;
    }
}
