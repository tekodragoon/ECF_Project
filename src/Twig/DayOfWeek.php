<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class DayOfWeek extends AbstractExtension
{
    public function getFilters():array
    {
        return [
            new TwigFilter('dayOfWeek', [$this, 'dayOfWeek']),
            new TwigFilter('frDayOfWeek', [$this, 'frDayOfWeek']),
        ];
    }

    public function dayOfWeek(int $day):string
    {
        $days = [
            0 => 'Monday',
            1 => 'Tuesday',
            2 => 'Wednesday',
            3 => 'Thursday',
            4 => 'Friday',
            5 => 'Saturday',
            6 => 'Sunday'
        ];
        return $days[$day];
    }

    public function frDayOfWeek(int $day):string
    {
        $days = [
            0 => 'Lundi',
            1 => 'Mardi',
            2 => 'Mercredi',
            3 => 'Jeudi',
            4 => 'Vendredi',
            5 => 'Samedi',
            6 => 'Dimanche'
        ];
        return $days[$day];
    }
}