<?php

namespace App\Twig;

use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class DayOfWeek extends AbstractExtension
{
    private TranslatorInterface $translator;
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }
    public function getFilters():array
    {
        return [
            new TwigFilter('dayOfWeek', [$this, 'dayOfWeek']),
            new TwigFilter('frDayOfWeek', [$this, 'frDayOfWeek']),
        ];
    }

    public function dayOfWeek(int $day):string
    {
        $days = [
            0 => $this->translator->trans('week.monday'),
            1 => $this->translator->trans('week.tuesday'),
            2 => $this->translator->trans('week.wednesday'),
            3 => $this->translator->trans('week.thursday'),
            4 => $this->translator->trans('week.friday'),
            5 => $this->translator->trans('week.saturday'),
            6 => $this->translator->trans('week.sunday'),
        ];
        return $days[$day];
    }
}