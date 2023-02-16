<?php

namespace App\Model;

use App\Entity\RecipeCategory;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class CategoryGroup
{
    public RecipeCategory|Collection $categories;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    /**
     * @return RecipeCategory|Collection
     */
    public function getCategories(): Collection|RecipeCategory
    {
        return $this->categories;
    }

    /**
     * @param RecipeCategory $category
     * @return CategoryGroup
     */
    public function addCategory(RecipeCategory $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }
        return $this;
    }

}