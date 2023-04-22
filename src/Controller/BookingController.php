<?php

namespace App\Controller;

use App\Entity\Restaurant;
use App\Form\BasicBookingType;
use App\Model\BasicBooking;
use App\Model\ReservedTable;
use App\Model\Service;
use App\Repository\ReservationRepository;
use App\Repository\RestaurantRepository;
use DateInterval;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{_locale}')]
class BookingController extends AbstractController
{
    /**
     * @throws NonUniqueResultException
     */
    #[Route('/booking', name: 'app_booking')]
    public function index(ReservationRepository $repository, RestaurantRepository $restaurantRepository): Response
    {
        $year = date('Y', strtotime('monday this week'));
        $month = date('m', strtotime('monday this week'));
        $day = date('d', strtotime('monday this week'));

        $monday = new DateTimeImmutable('monday this week');
        $reservations = $repository->findWeek(DateTime::createFromImmutable($monday));
        $restaurant = $restaurantRepository->findRestaurant();
        if (!$restaurant) {
            throw $this->createNotFoundException(
                'Restaurant\'s data can\'t be found. Contact support.'
            );
        }
        $services = $this->setServices($monday, $reservations, $restaurant);

        return $this->render('booking/index.html.twig', [
            'year' => $year,
            'month' => $month,
            'day' => $day,
            'services' => $services,
            'restaurant' => $restaurant,
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route('/booking/{date}', name: 'app_booking_date')]
    public function indexDate(string                $date,
                              ReservationRepository $repository,
                              RestaurantRepository  $restaurantRepository,
    ): Response
    {
        $year = date('Y', strtotime($date));
        $month = date('m', strtotime($date));
        $day = date('d', strtotime($date));

        $monday = new DateTimeImmutable($date);
        $reservations = $repository->findWeek(DateTime::createFromImmutable($monday));
        $restaurant = $restaurantRepository->findRestaurant();
        if (!$restaurant) {
            throw $this->createNotFoundException(
                'Restaurant\'s data can\'t be found. Contact support.'
            );
        }
        $services = $this->setServices($monday, $reservations, $restaurant);

        return $this->render('booking/show-date.html.twig', [
            'year' => $year,
            'month' => $month,
            'day' => $day,
            'services' => $services,
            'restaurant' => $restaurant,
        ]);
    }

    /**
     * @throws NonUniqueResultException
     * @throws Exception
     */
    #[Route('/booking/hours/{date}/{time}', name: 'app_booking_hours')]
    public function chooseHours(string                $date,
                                string                $time,
                                RestaurantRepository  $restaurantRepository,
                                ReservationRepository $reservationRepository,
                                Request               $request,
    ): Response
    {
        if ($time == 1200) {
            $noon = true;
        } elseif ($time == 1900) {
            $noon = false;
        } else {
            throw $this->createNotFoundException(
                'Parameter error.'
            );
        }
        $restaurant = $restaurantRepository->findRestaurant();
        if (!$restaurant) {
            throw $this->createNotFoundException(
                'Restaurant\'s data can\'t be found. Contact support.'
            );
        }
        $year = date('Y', strtotime($date));
        $month = date('m', strtotime($date));
        $day = date('d', strtotime($date));

        $reservations = $reservationRepository->findService(DateTime::createFromImmutable(new DateTimeImmutable($date)), $noon);

        // Create a service
        $service = new Service();
        $service->setNoon($noon);
        // Add all tables to the service
        $tables = $restaurant->getTables();
        foreach ($tables as $table) {
            $reservedTable = new ReservedTable();
            $reservedTable->setTable($table);
            $service->addReservedTable($reservedTable);
        }
        // Set all tables that are already reserved
        foreach ($reservations as $reservation) {
            $reservedTables = $reservation->getReservedTables();
            foreach ($reservedTables as $reservedTable) {
                $service->reserveTable($reservedTable);
            }
        }

        $dayDate = date('N', strtotime($date)) - 1;
        $openHour = $restaurant->getOpenHours()[$dayDate];

        $booking = new BasicBooking();
        $form = $this->createForm(BasicBookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('app_booking_guests', [
                'guests' => $booking->getNumGuests(),
                'date' => $booking->getHour(),
            ]);
        }

        return $this->render('booking/select-hours.html.twig', [
            'year' => $year,
            'month' => $month,
            'day' => $day,
            'noon' => $noon,
            'openHour' => $openHour,
            'service' => $service,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/booking/guests/{guests}-{date}', name: 'app_booking_guests')]
    public function selectGuests(string $date, string $guests): Response
    {
        return $this->render('booking/select-guests.html.twig', [
            'date' => $date,
            'guests' => $guests,
        ]);
    }

    /**
     * @param DateTimeImmutable $monday
     * @param array $reservations
     * @param Restaurant $restaurant
     * @return array
     */
    private function setServices(DateTimeImmutable $monday, array $reservations, Restaurant $restaurant): array
    {
        $services = [];

        for ($i = 0; $i < 7; $i++) {
            $noonService = new Service();
            $eveningService = new Service();
            $interval = DateInterval::createFromDateString($i . ' day');
            $curDate = DateTime::createFromImmutable($monday);
            $curDate->add($interval);
            $noonService->setDate($curDate);
            $eveningService->setDate($curDate);
            $noonService->setNoon(true);
            $eveningService->setNoon(false);

            $tables = $restaurant->getTables();
            foreach ($tables as $table) {
                $reservedTable = new ReservedTable();
                $reservedTable->setTable($table);
                $noonService->addReservedTable($reservedTable);
                $eveningService->addReservedTable(clone $reservedTable);
            }

            $services[] = $noonService;
            $services[] = $eveningService;
        }

        foreach ($reservations as $reservation) {
            $date = DateTime::createFromImmutable($reservation->getDate());
            $noon = $reservation->isNoonService();
            $tables = $reservation->getReservedTables();
            $filter_func = function (Service $service) use ($date, $noon) {
                return $service->getDate() == $date && $service->isNoon() === $noon;
            };
            $index = array_keys(array_filter($services, $filter_func))[0];

            foreach ($tables as $table) {
                $services[$index]->reserveTable($table);
            }
        }

        return $services;
    }
}
