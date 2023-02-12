<?php

namespace App\Model;

use Doctrine\Common\Collections\ArrayCollection;

class RecipeListGroup
{
    private RecipeList|ArrayCollection $recipeLists;

    public function __construct()
    {
        $this->recipeLists = new ArrayCollection();
    }

    /**
     * @return RecipeList|ArrayCollection
     */
    public function getRecipeLists(): RecipeList|ArrayCollection
    {
        return $this->recipeLists;
    }

    /**
     * @param RecipeList $recipeList
     * @return RecipeListGroup
     */
    public function addRecipeList(RecipeList $recipeList): self
    {
        if (!$this->recipeLists->contains($recipeList)) {
            $this->recipeLists->add($recipeList);
        }
        return $this;
    }

}