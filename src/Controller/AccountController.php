<?php

namespace App\Controller;

use App\Entity\Guest;
use App\Entity\User;
use App\Form\GuestType;
use App\Form\PasswordEditType;
use App\Form\UserGuestType;
use App\Form\UserType;
use App\Repository\GuestRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/account')]
class AccountController extends AbstractController
{
    #[Route('/', name: 'app_account')]
    public function account(): Response
    {
        return $this->render('account/account.html.twig');
    }

    #[Route('/show', name: 'app_account_show')]
    public function showAccount(): Response
    {
        return $this->render('account/show_account.html.twig');
    }

    #[Route('/edit', name: 'app_account_edit')]
    public function editAccount(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('app_account_show');
        }

        return $this->render('account/edit_account.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit-password', name: 'app_account_edit_pwd')]
    public function updatePwd(
        Request                     $request,
        EntityManagerInterface      $manager,
        UserRepository              $repo,
        UserPasswordHasherInterface $hasher): Response
    {
        $form = $this->createForm(PasswordEditType::class);
        $form->handleRequest($request);

        $user = $repo->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        dump($user);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $hasher->hashPassword(
                    $user,
                    $form->get('newPassword')->getData()
                ));
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('app_account_show');
        }

        return $this->render('account/edit_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/guest-list', name: 'app_account_guests')]
    public function showGuest(): Response
    {
        return $this->render('account/show_guest.html.twig');
    }

    #[Route('/edit-user-guest/{id}', name: 'app_account_edit_user-guest')]
    public function editUserGuest(Request $request, User $user, UserRepository $repo): Response
    {
        $form = $this->createForm(UserGuestType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repo->save($user, true);
            return $this->redirectToRoute('app_account_guests');
        }

        return $this->render('account/edit_guest.html.twig', [
            'form' => $form->createView(),
            'path' => $this->generateUrl('app_account_edit_user-guest', [
                'id' => $user->getId(),
            ]),
            'buttonTitle' => 'Modifier',
        ]);
    }

    #[Route('/edit-guest/{id}', name: 'app_account_edit_guest')]
    public function editGuest(Request $request, Guest $guest, GuestRepository $repo): Response
    {
        $form = $this->createForm(GuestType::class, $guest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repo->save($guest, true);
            return $this->redirectToRoute('app_account_guests');
        }

        return $this->render('account/edit_guest.html.twig', [
            'form' => $form->createView(),
            'path' => $this->generateUrl('app_account_edit_guest', [
                'id' => $guest->getId(),
            ]),
            'buttonTitle' => 'Modifier',
        ]);
    }

    #[Route('/add-guest', name: 'app_account_add_guest')]
    public function addGuest(Request $request, GuestRepository $guestRepo, UserRepository $userRepo): Response
    {
        $guest = new Guest();
        $user = $userRepo->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        $guest->setUser($user);
        $form = $this->createForm(GuestType::class, $guest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $guestRepo->save($guest, true);
            return $this->redirectToRoute('app_account_guests');
        }

        return $this->render('account/edit_guest.html.twig', [
            'form' => $form->createView(),
            'path' => $this->generateUrl('app_account_add_guest'),
            'buttonTitle' => 'Ajouter',
        ]);
    }

    #[Route('/remove-guest/{id}', name: 'app_account_rem_guest')]
    public function removeGuest(Request $request, Guest $guest, GuestRepository $guestRepo): Response
    {
        $guestRepo->remove($guest, true);
        return $this->redirectToRoute('app_account_guests');
    }

    #[Route('/confirm-delete/{id}', name: 'app_confirm_del')]
    public function confirmDelete(Guest $guest): Response
    {
        return $this->render('account/_confirm-delete.html.twig', [
            'id' => $guest->getId(),
        ]);
    }

    #[Route('/delete-guest/{id}', name: 'app_del-guest')]
    public function deleteGuest(Guest $guest): Response
    {
        return $this->render('account/_delete-guest.html.twig', [
            'id' => $guest->getId(),
        ]);
    }
}
