<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Model\RecipeList;
use App\Model\RecipeListGroup;
use App\RecipeType;
use App\Repository\MenuRepository;
use App\Repository\RecipeCategoryRepository;
use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{_locale}')]
class MenuController extends AbstractController
{
    #[Route('/menu', name: 'app_menu')]
    public function index(MenuRepository $menuRepository, RecipeRepository $recipeRepository, RecipeCategoryRepository $catRepo): Response
    {
        $menus = $menuRepository->findActiveMenu(true);

        $recipeLists = new RecipeListGroup();
        $categories = $catRepo->findBy([], ['listOrder' => 'ASC']);

        foreach ($categories as $category) {
            $recipes = $recipeRepository->findByCategory($category);
            $recipeList = new RecipeList();
            $recipeList->setCategory($category->getName());
            $recipeList->setCategoryOrder($category->getListOrder());
            foreach ($recipes as $recipe) {
                $recipeList->addRecipe($recipe);
            }
            $recipeLists->addRecipeList($recipeList);
        }

        return $this->render('menu/index.html.twig', [
            'menus' => $menus,
            'recipeLists' => $recipeLists->getRecipeLists(),
        ]);
    }
}
