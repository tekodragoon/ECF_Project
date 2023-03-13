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
                'label' => 'Ouverture',
                'choices' => [
                    'Ouvert' => true,
                    'Fermé' => false,
                ]
            ])
            ->add('noonService', ChoiceType::class, [
                'label' => 'Service le midi',
                'choices' => [
                    'Service' => true,
                    'Pas de service' => false,
                ]
            ])
            ->add('eveningService', ChoiceType::class, [
                'label' => 'Service le soir',
                'choices' => [
                    'Service' => true,
                    'Pas de service' => false,
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
