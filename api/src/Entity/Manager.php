<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Manager
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    protected ?string $name = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    protected ?Badge $badge = null;

    #[ORM\Column(nullable: true)]
    protected ?int $seniority = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getBadge(): ?Badge
    {
        return $this->badge;
    }

    public function setBadge(?Badge $subResource): static
    {
        $this->badge = $subResource;

        return $this;
    }

    public function getSeniority(): ?int
    {
        return $this->seniority;
    }

    public function setSeniority(?int $seniority): self
    {
        $this->seniority = $seniority;
        return $this;
    }
}
