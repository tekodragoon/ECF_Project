<?php

namespace App\Form;

use App\Entity\OpeningDays;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OpeningDaysType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('open', ChoiceType::class, [
                'label' => 'Opening',
                'choices' => [
                    'Open' => true,
                    'Closed' => false,
                ]
            ])
            ->add('noonService', ChoiceType::class, [
                'label' => 'Noon Service',
                'choices' => [
                    'Service' => true,
                    'No service' => false,
                ]
            ])
            ->add('eveningService', ChoiceType::class, [
                'label' => 'Evening Service',
                'choices' => [
                    'Service' => true,
                    'No service' => false,
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OpeningDays::class,
        ]);
    }
}
