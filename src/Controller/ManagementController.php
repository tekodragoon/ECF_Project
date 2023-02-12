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
                $this->addFlash('success', 'Modifications enregistrées');
                foreach ($menuGroup->activeMenus as $menuItem) {
                    $menu = $menuRepository->findById($menuItem->getMenuId());
                    $menu->setActive($menuItem->isActive());
                    $menuRepository->save($menu, true);
                }
            } else {
                $this->addFlash('error', 'Trop de menus actifs. Sélectionnez-en au maximum 3');
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
