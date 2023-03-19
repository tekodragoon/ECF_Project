<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class DayOfWeek extends AbstractExtension
{
    public function getFilters():array
    {
        return [
            new TwigFilter('dayOfWeek', [$this, 'dayOfWeek'])
        ];
    }

    public function dayOfWeek(string $day):string
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
}