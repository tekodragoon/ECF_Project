<?php

namespace App\Controller;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{_locale}')]
class BookingController extends AbstractController
{
    #[Route('/booking', name: 'app_booking')]
    public function index(): Response
    {
        $year = date('Y',strtotime('last monday'));
        $month = date('m',strtotime('last monday'));
        $day = date('d',strtotime('last monday'));

        return $this->render('booking/index.html.twig', [
            'year' => $year,
            'month' => $month,
            'day' => $day,
        ]);
    }

    #[Route('/booking/{date}', name: 'app_booking_date')]
    public function indexDate(string $date): Response
    {
        $year = date('Y',strtotime($date));
        $month = date('m',strtotime($date));
        $day = date('d',strtotime($date));

        return $this->render('booking/show-date.html.twig', [
            'year' => $year,
            'month' => $month,
            'day' => $day,
        ]);
    }
}
