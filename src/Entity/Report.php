<?php

namespace App\Entity;

use App\Entity\Traits\UUIDTrait;
use App\Repository\ReportRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

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
}
