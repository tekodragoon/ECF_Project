<?php

namespace App\Controller;

use App\Entity\GalleryImage;
use App\Entity\Menu;
use App\Entity\Recipe;
use App\Entity\RecipeCategory;
use App\Form\ActiveMenuGroupType;
use App\Form\CategoryOrderGroupType;
use App\Form\CategoryType;
use App\Form\MenuType;
use App\Form\RecipeType;
use App\Model\ActiveMenu;
use App\Model\ActiveMenuGroup;
use App\Model\CategoryGroup;
use App\Repository\GalleryImageRepository;
use App\Repository\MenuRepository;
use App\Repository\RecipeCategoryRepository;
use App\Repository\RecipeRepository;
use App\Service\GalleryService;
use App\Service\WarmUpCacheService;
use Doctrine\ORM\NonUniqueResultException;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/manager')]
class ManagementController extends AbstractController
{
    private GalleryService $galleryService;

    public function __construct(GalleryService $galleryService)
    {
        $this->galleryService = $galleryService;
    }

    #[Route('/', name: 'app_management')]
    public function index(ParameterBagInterface $bag, MessageBusInterface $bus): Response
    {
        $cache = new WarmUpCacheService($bag, $bus);
        $cache->createCacheImages();
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
    #[Route('/manage-menu', name: 'app_management_menu')]
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
            if ($menuGroup->getActivesCount() <= 3 && $menuGroup->getActivesCount() > 0) {
                foreach ($menuGroup->activeMenus as $menuItem) {
                    $menu = $menuRepository->findById($menuItem->getMenuId());
                    $menu->setActive($menuItem->isActive());
                    $menuRepository->save($menu, true);
                }
                $this->addFlash('success', 'Modifications enregistrées.');
            } else {
                if ($menuGroup->getActivesCount() > 3) {
                    $this->addFlash('error', 'Il y a trop de menus actifs. Sélectionnez-en au maximum 3.');
                }
                if ($menuGroup->getActivesCount() == 0) {
                    $this->addFlash('error', 'Aucun menus actifs. Sélectionnez-en au minimum 1.');
                }
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
            $this->addFlash('success', $menu->getTitle() . ' a été créer.');

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
    public function removeGuest(Menu $menu, MenuRepository $menuRepo): Response
    {
        $name = $menu->getTitle();
        $menuRepo->remove($menu, true);
        $this->addFlash('success', 'Le menu ' . $name . ' a bien été supprimé.');

        return $this->redirectToRoute('app_management_menu');
    }

    // ------------------------------------------------------------------------- SECTION RECIPE
    // -------------------------------------------------------------------------
    // -------------------------------------------------------------------------
    // -------------------------------------------------------------------------

    #[Route('/manage-recipe', name: 'app_management_recipe')]
    public function recipe(RecipeRepository $recipeRepository): Response
    {
        $recipes = $recipeRepository->findAllByCategory();

        return $this->render('management/recipe/recipe-gestion.html.twig', [
            'recipes' => $recipes,
        ]);
    }

    #[Route('/edit-recipe/{id}', name: 'app_management_edit-recipe')]
    public function editRecipe(Request $request, Recipe $recipe, RecipeRepository $repository): Response
    {
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($recipe, true);
            $this->addFlash('success', $recipe->getName() . ' mis à jour.');

            return $this->redirectToRoute('app_management_recipe');
        }

        return $this->render('management/recipe/edit-recipe.html.twig', [
            'path' => $this->generateUrl('app_management_edit-recipe', [
                'id' => $recipe->getId(),
            ]),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/add-recipe', name: 'app_management_add-recipe')]
    public function addRecipe(Request $request, RecipeRepository $repository): Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($recipe, true);
            $this->addFlash('success', $recipe->getName() . ' a été ajoutée.');

            return $this->redirectToRoute('app_management_recipe');
        }

        return $this->render('management/recipe/edit-recipe.html.twig', [
            'path' => $this->generateUrl('app_management_add-recipe'),
            'form' => $form->createView(),
        ]);
    }

    // Show confirm template for deleting recipe
    #[Route('/confirm-delete-recipe/{id}', name: 'app_management_confirm_del_recipe')]
    public function confirmDeleteRecipe(Recipe $recipe): Response
    {
        return $this->render('management/recipe/_confirm-delete-recipe.html.twig', [
            'id' => $recipe->getId(),
        ]);
    }

    // Show manage recipe button
    #[Route('/manage-recipe/{id}', name: 'app_management_manage_recipe')]
    public function manageRecipe(Recipe $recipe): Response
    {
        return $this->render('management/recipe/_manage-recipe.html.twig', [
            'id' => $recipe->getId(),
        ]);
    }

    // Remove recipe and redirect to recipe gestion
    #[Route('/remove-recipe/{id}', name: 'app_management_rem_recipe')]
    public function removeRecipe(Recipe $recipe, RecipeRepository $repository): Response
    {
        $name = $recipe->getName();
        $repository->remove($recipe, true);
        $this->addFlash('success', $name . ' a bien été supprimé.');

        return $this->redirectToRoute('app_management_recipe');
    }

    // ------------------------------------------------------------------------- SECTION CATEGORY
    // -------------------------------------------------------------------------
    // -------------------------------------------------------------------------
    // -------------------------------------------------------------------------

    #[Route('/manage-category', name: 'app_management_recipe-category')]
    public function recipeCategory(RecipeCategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findBy([], ['listOrder' => 'ASC']);

        return $this->render('management/recipe/category-gestion.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/edit-category/{id}', name: 'app_management_edit_category')]
    public function editCategory(Request $request, RecipeCategory $category, RecipeCategoryRepository $categoryRepository): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepository->save($category, true);
            $this->addFlash('success', 'Catégorie mise à jour.');

            return $this->redirectToRoute('app_management_recipe-category');
        }

        return $this->render('management/recipe/_edit-category.html.twig', [
            'form' => $form->createView(),
            'id' => $category->getId(),
        ]);
    }

    #[Route('/show-category/{id}', name: 'app_management_show_category')]
    public function showCategory(RecipeCategory $category): Response
    {
        return $this->render('management/recipe/_show-category.html.twig', [
            'id' => $category->getId(),
            'name' => $category->getName(),
            'allowDelete' => $category->allowDelete(),
        ]);
    }

    #[Route('/add-category', name: 'app_management_add_category')]
    public function addCategory(Request $request, RecipeCategoryRepository $categoryRepository): Response
    {
        $category = new RecipeCategory();
        $categories = $categoryRepository->findAll();
        $category->setListOrder(count($categories) + 1);
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepository->save($category, true);
            $this->addFlash('success', $category->getName() . ' a été ajouté.');

            return $this->redirectToRoute('app_management_recipe-category');
        }

        return $this->render('management/recipe/_add-category.html.twig', [
            'form' => $form->createView(),
            'next' => $category->getListOrder(),
        ]);
    }

    // Show confirm template for deleting category
    #[Route('/confirm-delete-category/{id}', name: 'app_management_confirm_del_category')]
    public function confirmDeleteCategory(RecipeCategory $category): Response
    {
        return $this->render('management/recipe/_confirm-delete-category.html.twig', [
            'id' => $category->getId(),
            'allowDelete' => $category->allowDelete(),
        ]);
    }

    // Show delete button category
    #[Route('/manage-category/{id}', name: 'app_management_manage_category')]
    public function manageCategory(RecipeCategory $category): Response
    {
        return $this->render('management/recipe/_manage-category.html.twig', [
            'id' => $category->getId(),
            'allowDelete' => $category->allowDelete(),
        ]);
    }

    // Remove category and redirect to recipe gestion
    #[Route('/remove-category/{id}', name: 'app_management_rem_category')]
    public function removeCategory(RecipeCategory $category, RecipeCategoryRepository $categoryRepository): Response
    {
        $name = $category->getName();
        $categories = $categoryRepository->findBy([], ['listOrder' => 'ASC']);

        $index = $category->getListOrder();
        $categoryRepository->remove($category, true);

        foreach ($categories as $category) {
            if ($category->getListOrder() > $index) {
                $i = $category->getListOrder() - 1;
                $category->setListOrder($i);
                $categoryRepository->save($category, true);
            }
        }

        $this->addFlash('success', 'La catégorie ' . $name . ' a bien été supprimé.');

        return $this->redirectToRoute('app_management_recipe-category');
    }

    #[Route('/category-order', name: 'app_management_reorder_category')]
    public function reorderCategory(Request $request, RecipeCategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findBy([], ['listOrder' => 'ASC']);
        $categoryGroup = new CategoryGroup();

        foreach ($categories as $category) {
            $categoryGroup->addCategory($category);
        }

        $form = $this->createForm(CategoryOrderGroupType::class, $categoryGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($categoryGroup->categories as $category) {
                $categoryRepository->save($category, true);
            }
            $this->addFlash('success', 'L\'ordre des catégories a bien été enregistré.');

            return $this->redirectToRoute('app_management_recipe-category');
        }

        return $this->render('management/recipe/category-order.html.twig', [
            'categories' => $categories,
            'form' => $form->createView(),
        ]);
    }

    // ------------------------------------------------------------------------- SECTION CATEGORY
    // -------------------------------------------------------------------------
    // -------------------------------------------------------------------------
    // -------------------------------------------------------------------------

    #[Route('/gallery', name: 'app_management_gallery')]
    public function gallery(GalleryImageRepository $repository):Response
    {
        $images = $repository->findAll();

        return $this->render('management/gallery/index.html.twig', [
            'images' => $images,
        ]);
    }

    #[Route('/gallery-toggle/{id}', name: 'app_management_toggle-visibility')]
    public function toggleVisibility(GalleryImage $image, GalleryImageRepository $repository): Response
    {
        $image->setVisible(!$image->isVisible());
        $repository->save($image, true);
        return $this->redirectToRoute('app_management_gallery');
    }

    #[Route('gallery-confirm/{id}', name: 'app_management_confirm-delete-image')]
    public function confirmDeleteImage(GalleryImage $image):Response
    {
        return $this->render('management/gallery/_confirm-del-image.html.twig', [
            'id' => $image->getId(),
        ]);
    }
    #[Route('/gallery-delete/{id}', name: 'app_management_delete-image')]
    public function deleteImage(GalleryImage $image):Response
    {
        return $this->render('management/gallery/_delete-image.html.twig', [
            'id' => $image->getId(),
        ]);
    }

    #[Route('/remove-image/{id}', name: 'app_management_remove-image')]
    public function removeImage(
        GalleryImage $image,
        GalleryImageRepository $repository,
        CacheManager $cacheManager,
    ):Response
    {
        // suppression de l'image dans le dossier gallery
        $imagePath = $this->galleryService->getDirectory().$image->getPath();
        $galSup = unlink($imagePath);

        if ($galSup) {
            // suppression du cache
            $relativePath = 'build/images/gallery/'.$image->getPath();
            $cacheManager->remove($relativePath);

            // suppression de l'image en bdd
            $repository->remove($image, true);
            $this->addFlash('success', 'L\'image a été correctement supprimé.');
        } else {
            $this->addFlash('error', 'Un problème est survenu lors de la suppression du fichier.');
        }

        return $this->redirectToRoute('app_management_gallery');
    }
}
