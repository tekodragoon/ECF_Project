<?php

namespace App\Controller;

use App\Repository\ReservationRepository;
use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{_locale}')]
class BookingController extends AbstractController
{
    #[Route('/booking', name: 'app_booking')]
    public function index(ReservationRepository $repository): Response
    {
        $year = date('Y',strtotime('monday this week'));
        $month = date('m',strtotime('monday this week'));
        $day = date('d',strtotime('monday this week'));

        $bookings = $repository->findWeek(new DateTime('monday this week'));

        return $this->render('booking/index.html.twig', [
            'year' => $year,
            'month' => $month,
            'day' => $day,
            'bookings' => $bookings,
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route('/booking/{date}', name: 'app_booking_date')]
    public function indexDate(string $date, ReservationRepository $repository): Response
    {
        $year = date('Y',strtotime($date));
        $month = date('m',strtotime($date));
        $day = date('d',strtotime($date));

        $bookings = $repository->findWeek(new DateTime($date));

        return $this->render('booking/show-date.html.twig', [
            'year' => $year,
            'month' => $month,
            'day' => $day,
            'bookings' => $bookings,
        ]);
    }
}
