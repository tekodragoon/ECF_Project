<?php

namespace App\Controller;

use App\Form\PasswordType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
    public function updatePwd(Request $request, ): Response
    {
        $form = $this->createForm(PasswordType::class);
        $form->handleRequest($request);

        return $this->render('account/edit_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
