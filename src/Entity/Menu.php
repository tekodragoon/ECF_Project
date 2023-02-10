<?php

namespace App\Entity;

use App\Repository\MenuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MenuRepository::class)]
class Menu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $price = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'menu', targetEntity: Composition::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $compositions;

    public function __construct()
    {
        $this->compositions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Composition>
     */
    public function getCompositions(): Collection
    {
        return $this->compositions;
    }

    public function addComposition(Composition $composition): self
    {
        if (!$this->compositions->contains($composition)) {
            $this->compositions->add($composition);
            $composition->setMenu($this);
        }

        return $this;
    }

    public function removeComposition(Composition $composition): self
    {
        if ($this->compositions->removeElement($composition)) {
            // set the owning side to null (unless already changed)
            if ($composition->getMenu() === $this) {
                $composition->setMenu(null);
            }
        }

        return $this;
    }
}
