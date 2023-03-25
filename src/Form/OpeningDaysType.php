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
                'label' => 'message.opening',
                'choices' => [
                    'message.open' => true,
                    'message.closed' => false,
                ]
            ])
            ->add('noonService', ChoiceType::class, [
                'label' => 'message.noonService',
                'choices' => [
                    'message.service' => true,
                    'message.noService' => false,
                ]
            ])
            ->add('eveningService', ChoiceType::class, [
                'label' => 'message.eveningService',
                'choices' => [
                    'message.service' => true,
                    'message.noService' => false,
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
