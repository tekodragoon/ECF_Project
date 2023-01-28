<?php

namespace App\Controller;

use App\Entity\Guest;
use App\Form\GuestType;
use App\Form\PasswordEditType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    #[Route('/account', name: 'app_account')]
    public function account(): Response
    {
        return $this->render('account/account.html.twig');
    }

    #[Route('/account/show', name: 'app_account_show')]
    public function showAccount(): Response
    {
        return $this->render('account/show_account.html.twig');
    }

    #[Route('/account/edit', name: 'app_account_edit')]
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

    #[Route('/account/edit-password', name: 'app_account_edit_pwd')]
    public function updatePwd(
        Request $request,
        EntityManagerInterface $manager,
        UserRepository $repo,
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

    #[Route('/account/guest-list', name: 'app_account_guests')]
    public function showGuest():Response
    {
        return $this->render('account/show_guest.html.twig');
    }

    #[Route('/account/edit-guest', name: 'app_account_edit_guest')]
    public function editGuest(Request $request): Response
    {
        $guest = new Guest();
        $form = $this->createForm(GuestType::class, $guest);



        return $this->render('account/edit_guest.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
