<?php

namespace App\Twig;

use Faker\Provider\Lorem;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class LoremTwig extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('lorem', [$this, 'lorem'])
        ];
    }

    /**
     * @param int $words
     * @return string
     */
    public function lorem(int $words = 3):string
    {
        $list = Lorem::words($words, false);
        return join(' ', $list);
    }
}