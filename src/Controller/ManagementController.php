<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Form\ActiveMenuGroupType;
use App\Form\MenuType;
use App\Model\ActiveMenu;
use App\Model\ActiveMenuGroup;
use App\Repository\MenuRepository;
use Doctrine\ORM\NonUniqueResultException;
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

    #[Route('/action', name: 'app_management_action')]
    public function showManagementMenu(): Response
    {
        return $this->render('management/section.html.twig');
    }

    // ------------------------------------------------------------------------- SECTION MENU
    // -------------------------------------------------------------------------
    // -------------------------------------------------------------------------
    // -------------------------------------------------------------------------

    /**
     * @throws NonUniqueResultException
     */
    #[Route('/manage-menu', name:'app_management_menu')]
    public function menu(Request $request, MenuRepository $menuRepository): Response
    {
        $menus = $menuRepository->findAll();
        $menuGroup = new ActiveMenuGroup();

        foreach ($menus as $menu) {
            $activeMenu = new ActiveMenu();
            $activeMenu->setName($menu->getTitle());
            $activeMenu->setMenuId($menu->getId());
            $activeMenu->setActive($menu->isActive());
            $menuGroup->addActiveMenus($activeMenu);
        }

        $form = $this->createForm(ActiveMenuGroupType::class, $menuGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($menuGroup->getActivesCount() <= 3) {
                $this->addFlash('success', 'Modifications enregistrées.');
                foreach ($menuGroup->activeMenus as $menuItem) {
                    $menu = $menuRepository->findById($menuItem->getMenuId());
                    $menu->setActive($menuItem->isActive());
                    $menuRepository->save($menu, true);
                }
            } else {
                $this->addFlash('error', 'Il y a trop de menus actifs. Sélectionnez-en au maximum 3.');
            }
            return $this->redirectToRoute('app_management_menu');
        }

        return $this->render('management/menu/menu-gestion.html.twig', [
            'menus' => $menus,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit-menu/{id}', name: 'app_management_edit_menu')]
    public function editMenu(Request $request, Menu $menu, MenuRepository $menuRepository): Response
    {
        $form = $this->createForm(MenuType::class, $menu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $menuRepository->save($menu, true);
            $this->addFlash('success', 'Modifications enregistrées.');
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

    // Show confirm template for deleting menu
    #[Route('/confirm-delete-menu/{id}', name: 'app_management_confirm_del_menu')]
    public function confirmDeleteMenu(Menu $menu): Response
    {
        return $this->render('management/menu/_confirm-del-menu.html.twig', [
            'id' => $menu->getId(),
        ]);
    }

    // Show delete button menu
    #[Route('/delete-menu/{id}', name: 'app_management_delete_menu')]
    public function deleteGuest(Menu $menu): Response
    {
        return $this->render('management/menu/_delete-menu.html.twig', [
            'id' => $menu->getId(),
        ]);
    }

    // Remove menu and redirect to menu gestion
    #[Route('/remove-menu/{id}', name: 'app_management_rem_menu')]
    public function removeGuest(Request $request, Menu $menu, MenuRepository $menuRepo): Response
    {
        $name = $menu->getTitle();
        $menuRepo->remove($menu, true);
        $this->addFlash('success', 'Le menu '. $name . ' a bien été supprimé.');
        return $this->redirectToRoute('app_management_menu');
    }

    // ------------------------------------------------------------------------- SECTION RECETTE
    // -------------------------------------------------------------------------
    // -------------------------------------------------------------------------
    // -------------------------------------------------------------------------

    #[Route('/manage-recipe', name: 'app_management_recipe')]
    public function recipe():Response
    {
        return $this->render('management/recipe/recipe-gestion.html.twig');
    }
}