<?php

namespace App\Form;

use App\Model\ReservationGroup;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationGroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('simpleUser', SimpleUserType::class, [
                'label' => false,
            ])
            ->add('simpleGuests', CollectionType::class, [
                'label' => false,
                'entry_type' => SimpleGuestType::class,
                'by_reference' => false,
                'entry_options' => [
                    'label' => false
                ],
                'allow_add' => false,
                'allow_delete' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ReservationGroup::class,
        ]);
    }
}
