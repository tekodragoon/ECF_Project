<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BasicBookingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numGuests', NumberType::class, [
                'label' => false,
                'html5' => true,
                'attr' => [
                    'min' => 1,
                ]
            ])
            ->add('hour', TimeType::class, [
                'label' => false,
                'hours' => [
                    10, 11, 12, 13, 14, 15, 16,17, 18, 19, 20, 21, 22, 23
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
            // Configure your form options here
        ]);
    }
}
