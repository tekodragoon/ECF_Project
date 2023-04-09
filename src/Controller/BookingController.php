<?php

namespace App\Controller;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookingController extends AbstractController
{
    #[Route('/reservation', name: 'app_booking')]
    public function index(): Response
    {
        $date = date('Y-m-d',strtotime('last monday'));


        return $this->render('booking/index.html.twig', [
            'current_date' => $date,
        ]);
    }
}
