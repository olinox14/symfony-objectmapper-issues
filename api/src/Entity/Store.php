<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Store
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected int $id;

    #[ORM\Column(length: 255)]
    protected string $title;

    #[ORM\Column(nullable: true)]
    protected ?int $surface = null;

    #[ORM\Column(nullable: true)]
    protected ?bool $published = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    protected ?\DateTimeInterface $openingDate = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    protected ?Manager $manager = null;

    #[ORM\OneToOne(inversedBy: 'store', cascade: ['persist', 'remove'])]
    protected ?Contact $contact = null;

    /** @var Collection<int, Toy> */
    #[ORM\OneToMany(targetEntity: Toy::class, mappedBy: 'store', cascade: ['persist', 'remove'], orphanRemoval: true)]
    protected Collection $toys;

    /** @var Collection<int, Category> */
    #[ORM\ManyToMany(targetEntity: Category::class, cascade: ['persist'])]
    protected Collection $categories;

    /**
     * @var Collection<int, Supplier>
     */
    #[ORM\ManyToMany(targetEntity: Supplier::class, inversedBy: 'stores', cascade: ['persist'])]
    protected Collection $suppliers;

    public function __construct()
    {
        $this->toys = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->suppliers = new ArrayCollection();
        $this->openingDate = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getSurface(): ?int
    {
        return $this->surface;
    }

    public function setSurface(?int $surface): static
    {
        $this->surface = $surface;

        return $this;
    }

    public function isPublished(): ?bool
    {
        return $this->published;
    }

    public function setPublished(?bool $published): static
    {
        $this->published = $published;

        return $this;
    }

    public function getOpeningDate(): ?\DateTimeInterface
    {
        return $this->openingDate;
    }

    public function setOpeningDate(?\DateTimeInterface $openingDate): static
    {
        $this->openingDate = $openingDate;

        return $this;
    }

    public function getManager(): ?Manager
    {
        return $this->manager;
    }

    public function setManager(?Manager $oneToOneRelation): static
    {
        $this->manager = $oneToOneRelation;

        return $this;
    }

    public function getContact(): ?Contact
    {
        return $this->contact;
    }

    public function setContact(?Contact $contact): static
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * @return Collection<int, Toy>
     */
    public function getToys(): Collection
    {
        return $this->toys;
    }

    public function addToy(Toy $relatedManyToOne): static
    {
        if (!$this->toys->contains($relatedManyToOne)) {
            $this->toys->add($relatedManyToOne);
            $relatedManyToOne->setStore($this);
        }

        return $this;
    }

    public function removeToy(Toy $relatedManyToOne): static
    {
        if ($this->toys->removeElement($relatedManyToOne)) {
            // set the owning side to null (unless already changed)
            if ($relatedManyToOne->getStore() === $this) {
                $relatedManyToOne->setStore(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $relatedManyToMany): static
    {
        if (!$this->categories->contains($relatedManyToMany)) {
            $this->categories->add($relatedManyToMany);
        }

        return $this;
    }

    public function removeCategory(Category $relatedManyToMany): static
    {
        $this->categories->removeElement($relatedManyToMany);

        return $this;
    }

    /**
     * @return Collection<int, Supplier>
     */
    public function getSuppliers(): Collection
    {
        return $this->suppliers;
    }

    public function addSupplier(Supplier $supplier): static
    {
        if (!$this->suppliers->contains($supplier)) {
            $this->suppliers->add($supplier);
            $supplier->addStore($this);
        }

        return $this;
    }

    public function removeSupplier(Supplier $supplier): static
    {
        if ($this->suppliers->removeElement($supplier)) {
            $supplier->removeStore($this);
        }

        return $this;
    }
}
