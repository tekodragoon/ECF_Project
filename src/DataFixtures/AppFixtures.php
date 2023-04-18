<?php

namespace App\DataFixtures;

use App\Entity\Reservation;
use App\Entity\SimpleGuest;
use App\Entity\SimpleUser;
use App\Model\ReservedTable;
use App\Model\Service;
use DateInterval;
use DateTime;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Faker;

class AppFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    private Faker\Generator $faker;
    private Utils $utils;

    public function __construct()
    {
        $this->faker = Faker\Factory::create('fr_FR');
        $this->utils = new Utils();
    }

    public function getDependencies(): array
    {
        return [
            RestaurantFixtures::class,
        ];
    }

    public static function getGroups(): array
    {
        return ['restaurant'];
    }

    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        // create reservations for one week

        for ($i = 0; $i < 6; $i++) {
            // create services for each day
            $noonService = new Service();
            $noonService->setNoon(true);
            $eveningService = new Service();
            $eveningService->setNoon(false);

            // add tables to services
            $tables = $this->getReference(RestaurantFixtures::RESTAURANT_REFERENCE)->getTables();
            foreach ($tables as $table) {
                $reservedTable = new ReservedTable();
                $reservedTable->setTable($table);
                $noonService->addReservedTable($reservedTable);
                $eveningService->addReservedTable(clone $reservedTable);
            }

            // setting date
            $interval = DateInterval::createFromDateString($i . ' day');
            $curDate = new DateTime('monday this week');
            $curDate->add($interval);
            $noonService->setDate($curDate);
            $eveningService->setDate($curDate);

            for ($j = 0; $j < 15; $j++) {
                // create reservation and set date
                $reservation = new Reservation();
                $reservation->setDate(DateTimeImmutable::createFromMutable($curDate));
                // randomly choose noon or evening service
                $isNoonService = random_int(0, 100) > 50;
                // set number of guests
                $numOfGuest = random_int(3, 10);
                // create simple user
                $simpleUser = new SimpleUser();
                $this->utils->setIdentity($simpleUser);
                // create guests
                for ($k = 1; $k < $numOfGuest; $k++) {
                    $guest = new SimpleGuest();
                    $guest->setFirstname($this->faker->firstName());
                    $simpleUser->addSimpleGuest($guest);
                    $manager->persist($guest);
                }
                $manager->persist($simpleUser);
                $reservation->setSimpleUser($simpleUser);
                $reservation->setNoonService($isNoonService);
                // place users in tables
                $service = $isNoonService ? $noonService : $eveningService;
                $tables = $service->getTableFor($numOfGuest);
                if ($tables) {
                    // check if reservation is possible
                    $success = true;
                    // reserve tables
                    foreach ($tables as $table) {
                        if (!$service->reserveTable($table)) {
                            // problem with reservation cancel it
                            $success = false;
                            break;
                        }
                        $reservation->addReservedTables($table);
                    }
                    if ($success) {
                        $manager->persist($reservation);
                    } else {
                        // cancel reservation
                        // delete users
                        foreach ($simpleUser->getSimpleGuests() as $guest) {
                            $manager->remove($guest);
                        }
                        $manager->remove($simpleUser);
                    }
                }
            }
        }

        $manager->flush();
    }
}
