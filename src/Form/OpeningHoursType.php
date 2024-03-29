<?php

namespace App\Form;

use App\Entity\OpeningHours;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OpeningHoursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('noonStart', TimeType::class, [
                'label' => 'message.noonOpening',
                'hours' => [
                    10, 11, 12, 13, 14, 15,
                ],
                'minutes' => [
                    0, 15, 30, 45
                ],
            ])
            ->add('noonEnd', TimeType::class, [
                'label' => 'message.noonClosing',
                'hours' => [
                    11, 12, 13, 14, 15, 16,
                ],
                'minutes' => [
                    0, 15, 30, 45
                ],
            ])
            ->add('eveningStart', TimeType::class, [
                'label' => 'message.eveningOpening',
                'hours' => [
                    18, 19, 20,
                ],
                'minutes' => [
                    0, 15, 30, 45
                ],
            ])
            ->add('eveningEnd', TimeType::class, [
                'label' => 'message.eveningClosing',
                'hours' => [
                    21, 22, 23,
                ],
                'minutes' => [
                    0, 15, 30, 45
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OpeningHours::class,
        ]);
    }
}
