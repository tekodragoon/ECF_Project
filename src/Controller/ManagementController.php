<?php

namespace App\Controller;

use App\Entity\GalleryImage;
use App\Entity\Menu;
use App\Entity\Recipe;
use App\Entity\RecipeCategory;
use App\Entity\User;
use App\Form\ActiveMenuGroupType;
use App\Form\CategoryOrderGroupType;
use App\Form\CategoryType;
use App\Form\GalleryImageType;
use App\Form\MenuType;
use App\Form\RecipeType;
use App\Form\RestaurantType;
use App\Form\TableRestaurantType;
use App\Form\UserRoleType;
use App\Model\ActiveMenu;
use App\Model\ActiveMenuGroup;
use App\Model\CategoryGroup;
use App\Model\UserRole;
use App\Repository\GalleryImageRepository;
use App\Repository\MenuRepository;
use App\Repository\RecipeCategoryRepository;
use App\Repository\RecipeRepository;
use App\Repository\RestaurantRepository;
use App\Repository\UserRepository;
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
use Symfony\Contracts\Translation\TranslatorInterface;

#[IsGranted('ROLE_ADMIN')]
#[Route('/{_locale}/manager')]
class ManagementController extends AbstractController
{
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
    public function menu(Request $request, MenuRepository $menuRepository, TranslatorInterface $translator): Response
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
                $this->addFlash('success', $translator->trans('message.changes'));
            } else {
                if ($menuGroup->getActivesCount() > 3) {
                    $this->addFlash('error', $translator->trans('message.tooManyMenu'));
                }
                if ($menuGroup->getActivesCount() == 0) {
                    $this->addFlash('error', $translator->trans('message.tooLessMenu'));
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
    public function editMenu(Request $request, Menu $menu, MenuRepository $menuRepository, TranslatorInterface $translator): Response
    {
        $form = $this->createForm(MenuType::class, $menu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $menuRepository->save($menu, true);
            $this->addFlash('success', $translator->trans('message.changes'));

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
    public function addMenu(Request $request, MenuRepository $menuRepository, TranslatorInterface $translator): Response
    {
        $menu = new Menu();
        $form = $this->createForm(MenuType::class, $menu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $menuRepository->save($menu, true);
            $this->addFlash('success', $translator->trans('message.menuCreated', ['%menu%' => $menu->getTitle()]));

            return $this->redirectToRoute('app_management_menu');
        }

        return $this->render('management/menu/edit-menu.html.twig', [
            'form' => $form->createView(),
            'path' => $this->generateUrl('app_management_add_menu'),
            'buttonTitle' => $translator->trans('button.add'),
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
    public function removeGuest(Menu $menu, MenuRepository $menuRepo, TranslatorInterface $translator): Response
    {
        $name = $menu->getTitle();
        $menuRepo->remove($menu, true);
        $this->addFlash('success', $translator->trans('message.menuRemoved', ['%menu%' => $name]));

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
    public function editRecipe(Request $request, Recipe $recipe, RecipeRepository $repository, TranslatorInterface $translator): Response
    {
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($recipe, true);
            $this->addFlash('success', $translator->trans('message.elementUpdated', ['%element%' => $recipe->getName()]));

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
    public function addRecipe(Request $request, RecipeRepository $repository, TranslatorInterface $translator): Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($recipe, true);
            $this->addFlash('success', $translator->trans('message.elementAdded', ['%element%' => $recipe->getName()]));

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
    public function removeRecipe(Recipe $recipe, RecipeRepository $repository, TranslatorInterface $translator): Response
    {
        $name = $recipe->getName();
        $repository->remove($recipe, true);
        $this->addFlash('success', $translator->trans('message.elementRemoved', ['%element%' => $name]));

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
    public function editCategory(Request $request,
                                 RecipeCategory $category,
                                 RecipeCategoryRepository $categoryRepository,
                                 TranslatorInterface $translator,
    ): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepository->save($category, true);
            $this->addFlash('success', $translator->trans('message.elementUpdated', ['%element%' => $category->getName()]));

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
    public function addCategory(Request $request, RecipeCategoryRepository $categoryRepository, TranslatorInterface $translator): Response
    {
        $category = new RecipeCategory();
        $categories = $categoryRepository->findAll();
        $category->setListOrder(count($categories) + 1);
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepository->save($category, true);
            $this->addFlash('success', $translator->trans('message.elementAdded', ['%element%' => $category->getName()]));

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
    public function removeCategory(RecipeCategory $category,
                                   RecipeCategoryRepository $categoryRepository,
                                   TranslatorInterface $translator,
    ): Response
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

        $this->addFlash('success', $translator->trans('message.elementRemoved', ['%element%' => $name]));

        return $this->redirectToRoute('app_management_recipe-category');
    }

    #[Route('/category-order', name: 'app_management_reorder_category')]
    public function reorderCategory(Request $request,
                                    RecipeCategoryRepository $categoryRepository,
                                    TranslatorInterface $translator
    ): Response
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
            $this->addFlash('success', $translator->trans('message.categoryOrder'));

            return $this->redirectToRoute('app_management_recipe-category');
        }

        return $this->render('management/recipe/category-order.html.twig', [
            'categories' => $categories,
            'form' => $form->createView(),
        ]);
    }

    // ------------------------------------------------------------------------- SECTION GALLERY
    // -------------------------------------------------------------------------
    // -------------------------------------------------------------------------
    // -------------------------------------------------------------------------

    #[Route('/gallery', name: 'app_management_gallery')]
    public function gallery(GalleryImageRepository $repository): Response
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
    public function confirmDeleteImage(GalleryImage $image): Response
    {
        return $this->render('management/gallery/_confirm-del-image.html.twig', [
            'id' => $image->getId(),
        ]);
    }

    #[Route('/gallery-delete/{id}', name: 'app_management_delete-image')]
    public function deleteImage(GalleryImage $image): Response
    {
        return $this->render('management/gallery/_delete-image.html.twig', [
            'id' => $image->getId(),
        ]);
    }

    #[Route('/remove-image/{id}', name: 'app_management_remove-image')]
    public function removeImage(
        GalleryImage           $image,
        GalleryImageRepository $repository,
        CacheManager           $cacheManager,
        TranslatorInterface   $translator,
    ): Response
    {
        // suppression du cache
        $relativePath = 'build/images/gallery/' . $image->getFilename();
        $cacheManager->remove($relativePath);

        // suppression de l'image en bdd
        $repository->remove($image, true);
        $this->addFlash('success', $translator->trans('message.pictureRemoved'));

        return $this->redirectToRoute('app_management_gallery');
    }

    #[Route('/add-image', name: 'app_management_add-image')]
    public function addImage(Request $request, GalleryImageRepository $repository): Response
    {
        $galleryImage = new GalleryImage();
        $form = $this->createForm(GalleryImageType::class, $galleryImage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $repository->save($galleryImage, true);

            return $this->redirectToRoute('app_management_gallery');
        }

        return $this->render('management/gallery/add-image.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // ------------------------------------------------------------------------- SECTION USER
    // -------------------------------------------------------------------------
    // -------------------------------------------------------------------------
    // -------------------------------------------------------------------------

    #[Route('/users-manage', name: 'app_management_users')]
    public function user(UserRepository $repository): Response
    {
        $users = $repository->findBy([], ['roles' => 'ASC']);

        return $this->render('management/user/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/user-edit-role/{id}', name: 'app_management_update-user-role')]
    public function updateUserRole(Request $request, User $user, UserRepository $repository): Response
    {
        $userRole = new UserRole();
        $userRole->setRole($user->getRoles()[0]);
        $form = $this->createForm(UserRoleType::class, $userRole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRoles([$userRole->getRole()]);
            $repository->save($user, true);
            $this->addFlash('success', 'Role updated');
            return $this->redirectToRoute('app_management_users');
        }

        return $this->render('management/user/edit-user-role.html.twig', [
            'form' => $form,
            'id' => $user->getId(),
        ]);
    }

    #[Route('/user-show/{id}', name: 'app_management_show-user-information')]
    public function showUserInformation(User $user): Response
    {
        return $this->render('management/user/_user-information.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/confirm-remove-user/{id}', name: 'app_management_confirm-remove-user')]
    public function confirmRemoveUser(User $user): Response
    {
        return $this->render('management/user/confirm-delete.html.twig', [
            'id' => $user->getId(),
        ]);
    }

    #[Route('/remove-user/{id}', name: 'app_management_remove-user')]
    public function removeUser(User $user, UserRepository $repository): Response
    {
        $repository->remove($user, true);
        $this->addFlash('success', 'User deleted.');
        return $this->redirectToRoute('app_management_users');
    }

    // ------------------------------------------------------------------------- SECTION RESTAURANT
    // -------------------------------------------------------------------------
    // -------------------------------------------------------------------------
    // -------------------------------------------------------------------------

    /**
     * @throws NonUniqueResultException
     */
    #[Route('/manage-rest', name: 'app_management_manage-restaurant')]
    public function manageRestaurant(RestaurantRepository $repository): Response
    {
        $restaurant = $repository->findRestaurant();
        if (!$restaurant) {
            throw $this->createNotFoundException(
                'Restaurant\'s data can\'t be found. Contact support.'
            );
        }

        return $this->render('management/restaurant/index.html.twig', [
            'restaurant' => $restaurant,
        ]);
    }

    /**
     * @throws NonUniqueResultException
     */
    #[Route('/manage-open-rest', name: 'app_management_open-restaurant')]
    public function manageOpenRestaurant(Request $request, RestaurantRepository $repository): Response
    {
        $restaurant = $repository->findRestaurant();
        if (!$restaurant) {
            throw $this->createNotFoundException(
                'Restaurant\'s data can\'t be found. Contact support.'
            );
        }
        $form = $this->createForm(RestaurantType::class, $restaurant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // check for close day
            foreach ($restaurant->getOpenDays() as $openDay) {
                if (!$openDay->isOpen()) { // if restaurant close
                    // set no service
                    $openDay->setNoonService(false);
                    $openDay->setEveningService(false);
                }
            }
            // remove hours data if necessary
            $h = $restaurant->getOpenHours();
            $d = $restaurant->getOpenDays();
            for ($i = 0; $i < 7; $i++) {
                if (!$d[$i]->isNoonService()) {
                    $h[$i]->setNoonStart(null);
                    $h[$i]->setNoonEnd(null);
                }
                if (!$d[$i]->isEveningService()) {
                    $h[$i]->setEveningStart(null);
                    $h[$i]->setEveningEnd(null);
                }
            }
            $repository->save($restaurant, true);
            $this->addFlash('success', 'Changes saved');
            return $this->redirectToRoute('app_management_manage-restaurant');
        }

        return $this->render('management/restaurant/edit-opening.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @throws NonUniqueResultException
     */
    #[Route('/manage-seats', name: 'app_management_manage-seats-restaurant')]
    public function manageSeatsRestaurant(Request $request, RestaurantRepository $repository):Response
    {
        $restaurant = $repository->findRestaurant();
        if (!$restaurant) {
            throw $this->createNotFoundException(
                'Restaurant\'s data can\'t be found. Contact support.'
            );
        }
        $form = $this->createForm(TableRestaurantType::class, $restaurant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($restaurant, true);
            $this->addFlash('success', 'Table\'s informations updated.');
            return $this->redirectToRoute('app_management_manage-restaurant');
        }

        return $this->render('management/restaurant/edit-table.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
