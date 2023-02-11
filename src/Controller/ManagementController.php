<?php

namespace App\Controller;

use App\Entity\Menu;
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

    #[Route('/edit-menu/{id}', name: 'app_management_edit_menu')]
    public function editMenu(Request $request, Menu $menu, MenuRepository $menuRepository): Response
    {
        $form = $this->createForm(MenuType::class, $menu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $menuRepository->save($menu, true);
            return $this->redirectToRoute('app_management_menu');
        }

        return $this->render('management/menu/edit-menu.html.twig', [
            'form' => $form->createView(),
            'path' => $this->generateUrl('app_management_edit_menu', [
                'id' => $menu->getId(),
            ])
        ]);
    }

    #[Route('/add-menu', name: 'app_management_add_menu')]
    public function addMenu(Request $request, MenuRepository $menuRepository): Response
    {
        $menu = new Menu();
        $form = $this->createForm(MenuType::class, $menu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $menuRepository->save($menu, true);
            return $this->redirectToRoute('app_management_menu');
        }

        return $this->render('management/menu/edit-menu.html.twig', [
            'form' => $form->createView(),
            'path' => $this->generateUrl('app_management_add_menu'),
            'buttonTitle' => 'Ajouter',
        ]);
    }
}
