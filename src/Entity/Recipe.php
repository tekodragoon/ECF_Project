<?php

namespace App\Entity;

use App\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $price = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?RecipeCategory $category = null;

    #[ORM\OneToOne(mappedBy: 'recipe', cascade: ['persist', 'remove'])]
    private ?GalleryImage $galleryImages = null;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    public function getCategory(): ?RecipeCategory
    {
        return $this->category;
    }

    public function setCategory(?RecipeCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getGalleryImages(): ?GalleryImage
    {
        return $this->galleryImages;
    }

    public function setGalleryImages(?GalleryImage $galleryImages): self
    {
        // unset the owning side of the relation if necessary
        if ($galleryImages === null && $this->galleryImages !== null) {
            $this->galleryImages->setRecipe(null);
        }

        // set the owning side of the relation if necessary
        if ($galleryImages !== null && $galleryImages->getRecipe() !== $this) {
            $galleryImages->setRecipe($this);
        }

        $this->galleryImages = $galleryImages;

        return $this;
    }
}
