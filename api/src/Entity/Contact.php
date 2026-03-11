<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Contact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    protected ?string $address = null;

    #[ORM\Column(length: 255, nullable: true)]
    protected ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    protected ?string $phone = null;

    #[ORM\OneToOne(mappedBy: 'contact', cascade: ['persist', 'remove'])]
    protected ?Store $store = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getStore(): ?Store
    {
        return $this->store;
    }

    public function setStore(?Store $store): static
    {
        // unset the owning side of the relation if necessary
        if ($store === null && $this->store !== null) {
            $this->store->setContact(null);
        }

        // set the owning side of the relation if necessary
        if ($store !== null && $store->getContact() !== $this) {
            $store->setContact($this);
        }

        $this->store = $store;

        return $this;
    }
}
