<?php

namespace App\Form;

use App\Entity\OpeningHours;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OpeningHoursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dayOfWeek', ChoiceType::class, [
                'label' => 'Jour de la semaine',
                'attr' => [
                    'disabled' => true,
                ],
                'choices' => [
                    'lundi' => 0,
                    'mardi' => 1,
                    'mercredi' => 2,
                    'jeudi' => 3,
                    'vendredi' => 4,
                    'samedi' => 5,
                    'dimanche' => 6,
                ]
            ])
            ->add('noonStart', TimeType::class, [
                'label' => 'Midi ouverture',
                'hours' => [
                    10, 11, 12, 13, 14, 15, 16,
                ],
                'minutes' => [
                    0, 15, 30, 45
                ],
            ])
            ->add('noonEnd', TimeType::class, [
                'label' => 'Midi fermeture',
                'hours' => [
                    10, 11, 12, 13, 14, 15, 16,
                ],
                'minutes' => [
                    0, 15, 30, 45
                ],
            ])
            ->add('eveningStart', TimeType::class, [
                'label' => 'Soir ouverture',
                'hours' => [
                    18, 19, 20, 21, 22, 23,
                ],
                'minutes' => [
                    0, 15, 30, 45
                ],
            ])
            ->add('eveningEnd', TimeType::class, [
                'label' => 'Soir fermeture',
                'hours' => [
                    18, 19, 20, 21, 22, 23,
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