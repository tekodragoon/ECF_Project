<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function editAccount(): Response
    {
        return $this->render('account/edit_account.html.twig');
    }
}
