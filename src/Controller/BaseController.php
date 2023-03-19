<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BaseController extends AbstractController
{
    #[Route('/')]
    public function home(): Response
    {
        return $this->redirectToRoute('app_home', ['_locale' => 'en']);
    }
}