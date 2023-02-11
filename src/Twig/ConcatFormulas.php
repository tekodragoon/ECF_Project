<?php

namespace App\Twig;

use Doctrine\ORM\PersistentCollection;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ConcatFormulas extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('concatFormulas', [$this, 'concatFormulas'])
        ];
    }

    /**
     * @param PersistentCollection $formulas
     * @return string
     */
    public function concatFormulas(PersistentCollection $formulas): string
    {
        if(count($formulas) == 0) return 'Aucune formule.';

        $str = '';
        foreach ($formulas as $form) {
            $str .= $form;
            $str .= ', ';
        }
        $str = substr($str, 0, -2);
        $str .= '.';
        return $str;
    }
}