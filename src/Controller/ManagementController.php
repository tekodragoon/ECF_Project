<?php

namespace App\Controller;

use App\Form\MenuType;
use App\Repository\MenuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/manager')]
class ManagementController extends AbstractController
{
    #[Route('/', name: 'app_management')]
    public function index(): Response
    {
        return $this->render('management/index.html.twig');
    }

    #[Route('/show', name: 'app_management_show')]
    public function showManagementMenu(): Response
    {
        return $this->render('management/section.html.twig');
    }

    #[Route('/manage-menu', name:'app_management_menu')]
    public function menu(Request $request, MenuRepository $menuRepository): Response
    {
        $menus = $menuRepository->findAll();

        return $this->render('management/menu/menu-gestion.html.twig', [
            'menus' => $menus,
        ]);
    }
}
