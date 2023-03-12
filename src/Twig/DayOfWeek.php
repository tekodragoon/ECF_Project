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