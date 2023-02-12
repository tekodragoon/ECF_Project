<?php

namespace App\Model;

use App\Entity\Recipe;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class RecipeList
{
    /**
     * @var string
     */
    private string $category;

    /**
     * @var int
     */
    private int $categoryOrder;

    /**
     * @var Collection|Recipe
     */
    private Recipe|Collection $recipes;

    public function __construct()
    {
        $this->recipes = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * @param string $category
     * @return RecipeList
     */
    public function setCategory(string $category): self
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return Recipe|array
     */
    public function getRecipes(): Recipe|Collection
    {
        return $this->recipes;
    }

    /**
     * @param Recipe $recipe
     * @return RecipeList
     */
    public function addRecipe(Recipe $recipe): self
    {
        if (!$this->recipes->contains($recipe)) {
            $this->recipes->add($recipe);
        }
        return $this;
    }

    /**
     * @return int
     */
    public function getCategoryOrder(): int
    {
        return $this->categoryOrder;
    }

    /**
     * @param int $categoryOrder
     * @return RecipeList
     */
    public function setCategoryOrder(int $categoryOrder): self
    {
        $this->categoryOrder = $categoryOrder;
        return $this;
    }
}