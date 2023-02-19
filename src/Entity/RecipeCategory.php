<?php

namespace App\Entity;

use App\Repository\RecipeCategoryRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecipeCategoryRepository::class)]
class RecipeCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $listOrder = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Recipe::class)]
    private Collection $recipes;

    /**
     * @return Collection
     */
    public function getRecipes(): Collection
    {
        return $this->recipes;
    }

    /**
     * @param Collection $recipes
     * @return RecipeCategory
     */
    public function setRecipes(Collection $recipes): RecipeCategory
    {
        $this->recipes = $recipes;
        return $this;
    }

    public function allowDelete(): bool
    {
        return $this->recipes->isEmpty();
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getListOrder(): ?int
    {
        return $this->listOrder;
    }

    public function setListOrder(int $listOrder): self
    {
        $this->listOrder = $listOrder;

        return $this;
    }
}
