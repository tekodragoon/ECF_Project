<?php

namespace App\Twig;

use App\Entity\Allergy;
use Doctrine\ORM\PersistentCollection;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ConcatAllergies extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('concatAllergies', [$this, 'concatAllergies'])
        ];
    }

    /**
     * @param PersistentCollection $allergies
     * @return string
     */
    public function concatAllergies(PersistentCollection $allergies): string
    {
        if(count($allergies) == 0) return 'Aucune allergie.';

        $str = '';
        foreach ($allergies as $allergy) {
            $str .= $allergy;
            $str .= ', ';
        }
        $str = substr($str, 0, -2);
        $str .= '.';
        return $str;
    }
}