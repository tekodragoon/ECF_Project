<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\Restaurant;
use App\Entity\SimpleGuest;
use App\Entity\SimpleUser;
use App\Form\BasicBookingType;
use App\Form\ReservationGroupType;
use App\Model\BasicBooking;
use App\Model\ReservationGroup;
use App\Model\ReservedTable;
use App\Model\Service;
use App\Repository\ReservationRepository;
use App\Repository\RestaurantRepository;
use App\Repository\SimpleGuestRepository;
use App\Repository\SimpleUserRepository;
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

    #[Route('/booking/confirm/{id}', name: 'app_booking_confirm')]
    public function confirmBooking(Reservation $reservation, SimpleGuestRepository $guestRepository): Response
    {
        $user = $reservation->getSimpleUser();
        $simpleGuests = $guestRepository->findBy(['simpleUser' => $user]);

        return $this->render('booking/confirm.html.twig', [
            'user' => $user,
            'simpleGuests' => $simpleGuests,
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route('/booking/{date}', name: 'app_booking_date')]
    public function indexDate(string $date, ReservationRepository $repository, RestaurantRepository $restaurantRepository): Response
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
    #[Route('/booking/hours/{date}?{time}', name: 'app_booking_hours')]
    public function chooseHours(string $date, string $time, RestaurantRepository $restaurantRepository, ReservationRepository $reservationRepository, Request $request): Response
    {
        $restaurant = $restaurantRepository->findRestaurant();
        if (!$restaurant) {
            throw $this->createNotFoundException(
                'Restaurant\'s data can\'t be found. Contact support.'
            );
        }
        $year = date('Y', strtotime($date));
        $month = date('m', strtotime($date));
        $day = date('d', strtotime($date));

        $reservations = $this->getReservations(DateTime::createFromImmutable(new DateTimeImmutable($date)), $time === 'noon', $reservationRepository);

        // Create a service
        $service = $this->createService($time === 'noon', $restaurant, $reservations);

        $dayDate = date('N', strtotime($date)) - 1;
        $openHour = $restaurant->getOpenHours()[$dayDate];

        $booking = new BasicBooking();
        $form = $this->createForm(BasicBookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('app_booking_guests', [
                'noon' => $time,
                'guests' => $booking->getNumGuests(),
                'time' => $booking->getHour(),
                'backDate' => $date,
                'backTime' => $time,
            ]);
        }

        return $this->render('booking/select-hours.html.twig', [
            'year' => $year,
            'month' => $month,
            'day' => $day,
            'noon' => $time,
            'openHour' => $openHour,
            'service' => $service,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route('/booking/guests/{noon}?{guests}?{time}?{backDate}?{backTime}', name: 'app_booking_guests')]
    public function selectGuests(string $noon, string $time, string $guests, Request $request, string $backDate, string $backTime, SimpleUserRepository $suRepo, SimpleGuestRepository $sgRepo, ReservationRepository $reservationRepository, RestaurantRepository $restaurantRepository): Response
    {
        // Create a Reservation Group Model
        $reservationGroup = new ReservationGroup();
        // Create a simple user
        $simpleUser = new SimpleUser();
        $reservationGroup->setSimpleUser($simpleUser);
        // add requested guests to the simple user
        for ($i = 1; $i < $guests; $i++) {
            $guest = new SimpleGuest();
            $simpleUser->addSimpleGuest($guest);
            $reservationGroup->addSimpleGuests($guest);
        }
        $reservationForm = $this->createForm(ReservationGroupType::class, $reservationGroup);
        $reservationForm->handleRequest($request);

        if ($reservationForm->isSubmitted() && $reservationForm->isValid()) {
            // Check if the reservation can be completed
            $restaurant = $restaurantRepository->findRestaurant();
            if (!$restaurant) {
                throw $this->createNotFoundException(
                    'Restaurant\'s data can\'t be found. Contact support.'
                );
            }
            // get reservations
            $reservations = $this->getReservations(DateTime::createFromImmutable(new DateTimeImmutable($backDate)), $noon === 'noon', $reservationRepository);
            // Create a service
            $service = $this->createService($noon === 'noon', $restaurant, $reservations);
            // Get tables for the reservation
            $tables = $service->getTableFor($reservationGroup->getNumGuests());
            $success = true;
            if ($tables) {
                // check if all tables can be reserved
                foreach ($tables as $table) {
                    if (!$service->reserveTable($table)) {
                        // problem with reservation cancel it
                        $success = false;
                        break;
                    }
                }
            }
            // if problem or no tables available
            if (!$success || !$tables) {
                $this->addFlash('error', 'Une erreur est survenue lors de la réservation. Veuillez réessayer.');
                return $this->redirectToRoute('app_booking');
            }
            // data is valid, save it
            $suRepo->save($simpleUser, true);
            foreach ($reservationGroup->getSimpleGuests() as $simpleGuest) {
                $sgRepo->save($simpleGuest, true);
            }
            $reservation = new Reservation();
            $reservation->setDate(new DateTimeImmutable($backDate));
            $reservation->setNoonService($noon === 'noon');
            $reservation->setSimpleUser($simpleUser);
            foreach ($tables as $table) {
                $reservation->addReservedTables($table);
            }
            $reservationRepository->save($reservation, true);
            return $this->redirectToRoute('app_booking_confirm', [
                'id' => $reservation->getId(),
            ]);
        }

        return $this->render('booking/select-guests.html.twig', [
            'noon' => $noon,
            'backDate' => $backDate,
            'backTime' => $backTime,
            'time' => $time,
            'guests' => $guests,
            'reservationForm' => $reservationForm->createView(),
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

    private function getReservations(DateTime $date, bool $noon, ReservationRepository $reservationRepository): mixed
    {
        return $reservationRepository->findService($date, $noon);
    }

    private function createService(bool $noon, Restaurant $restaurant, mixed $reservations): Service
    {
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
        return $service;
    }
}
