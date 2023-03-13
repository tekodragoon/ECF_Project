<?php

namespace App\Form;

use App\Entity\Restaurant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RestaurantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('openHours', CollectionType::class, [
                'entry_type' => OpeningHoursType::class,
                'by_reference' => false,
                'entry_options' => [
                    'label' => false
                ],
            ])
            ->add('openDays', CollectionType::class, [
                'entry_type' => OpeningDaysType::class,
                'by_reference' => false,
                'entry_options' => [
                    'label' => false
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Restaurant::class,
        ]);
    }
}
