<?php

namespace App\Twig;

use Doctrine\ORM\PersistentCollection;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ConcatCompositions extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('concatCompositions', [$this, 'concatCompositions'])
        ];
    }

    /**
     * @param PersistentCollection $compositions
     * @return string
     */
    public function concatCompositions(PersistentCollection $compositions): string
    {
        if(count($compositions) == 0) return 'Aucune composition.';

        $str = '';
        foreach ($compositions as $comp) {
            $str .= $comp;
            $str .= ', ';
        }
        $str = substr($str, 0, -2);
        $str .= '.';
        return $str;
    }
}