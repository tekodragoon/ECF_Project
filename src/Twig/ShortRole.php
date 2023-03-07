<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class ShortRole extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('shortRole', [$this, 'shortRole'])
        ];
    }

    /**
     * @param string $role
     * @return string
     */
    public function shortRole(string $role): string
    {
        $str = str_replace('ROLE_', '', $role);
        return ucfirst(strtolower($str));
    }
}