<?php

namespace App\Controller;

use App\RecipeType;
use App\Repository\MenuRepository;
use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MenuController extends AbstractController
{
    #[Route('/menu', name: 'app_menu')]
    public function index(MenuRepository $menuRepository, RecipeRepository $recipeRepository): Response
    {
        $menus = $menuRepository->findActiveMenu(true);
        $entrees = $recipeRepository->findByType(RecipeType::entree);
        $main = $recipeRepository->findByType(RecipeType::main);
        $desserts = $recipeRepository->findByType(RecipeType::dessert);

        return $this->render('menu/index.html.twig', [
            'menus' => $menus,
            'entrees' => $entrees,
            'main' => $main,
            'desserts' => $desserts,
        ]);
    }
}
