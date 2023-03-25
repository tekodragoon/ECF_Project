<?php

namespace App\Twig;

use Doctrine\ORM\PersistentCollection;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ConcatAllergies extends AbstractExtension
{
    private TranslatorInterface $translator;
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }
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
        if(count($allergies) == 0) return $this->translator->trans('message.noAllergy');;

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