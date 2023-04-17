<?php

namespace App\DataFixtures;

use App\Entity\OpeningDays;
use App\Entity\OpeningHours;
use App\Entity\Restaurant;
use App\Entity\Table;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class RestaurantFixtures extends Fixture implements FixtureGroupInterface
{
    public const RESTAURANT_REFERENCE = 'restaurant';

    public static function getGroups(): array
    {
        return ['restaurant'];
    }

    public function load(ObjectManager $manager): void
    {
        $restaurant = new Restaurant();

        for ($i = 0; $i < 7; $i++) {
            $openHour = new OpeningHours();
            $openHour->setDayOfWeek($i);
            $openDay = new OpeningDays();
            $openDay->setDayOfWeek($i);
            if ($i <= 4) {
                $openDay->setOpen(true);
                $openDay->setNoonService(true);
                $openHour->setNoonStart(DateTime::createFromFormat('H:i', "11:30"));
                $openHour->setNoonEnd(DateTime::createFromFormat('H:i', "15:00"));
            }
            $openDay->setOpen(true);
            $openDay->setEveningService(true);
            $openHour->setEveningStart(DateTime::createFromFormat('H:i', "19:00"));
            $openHour->setEveningEnd(DateTime::createFromFormat('H:i', "23:00"));
            $restaurant->addOpenHour($openHour);
            $restaurant->addOpenDay($openDay);
        }

        // create tables
        for ($i = 0; $i < 15; $i++) {
            $table = new Table();
            if ($i < 8) {
                $seats = 4;
            } elseif ($i < 13) {
                $seats = 6;
            } else {
                $seats = 8;
            }
            $table->setSeats($seats);
            $restaurant->addTable($table);
        }

        $manager->persist($restaurant);
        $manager->flush();
        $this->addReference(self::RESTAURANT_REFERENCE, $restaurant);
    }
}