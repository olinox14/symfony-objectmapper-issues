<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Toy
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    protected string $description;

    #[ORM\Column(nullable: true)]
    protected ?float $price = null;

    #[ORM\ManyToOne(targetEntity: Store::class, inversedBy: 'toys')]
    #[ORM\JoinColumn(nullable: true)]
    protected ?Store $store = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getStore(): ?Store
    {
        return $this->store;
    }

    public function setStore(?Store $mainEntity): static
    {
        $this->store = $mainEntity;

        return $this;
    }
}
