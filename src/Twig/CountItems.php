<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CountItems extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('countItems', [$this, 'countItems'])
        ];
    }

    /**
     * @param array $items
     * @return array
     */
    public function countItems(array $items): array
    {
        return array_count_values($items);
    }
}