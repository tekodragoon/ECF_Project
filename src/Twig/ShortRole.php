<?php

namespace App\Twig;

use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class ShortRole extends AbstractExtension
{
    private TranslatorInterface $translator;
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }
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
        return $this->translator->trans(strtolower($role));
    }
}