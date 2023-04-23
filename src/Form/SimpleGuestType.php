<?php

namespace App\Form;

use App\Entity\SimpleGuest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SimpleGuestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'account.firstname',
                'required' => false,
            ])
            ->add('adult', ChoiceType::class, [
                'label' => 'account.adult',
                'choices' => [
                    'account.adult' => true,
                    'account.child' => false,
                ],
                'expanded' => false,
                'multiple' => false,
            ])
            ->add('allergies', TextType::class, [
                'label' => 'account.allergy',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SimpleGuest::class,
        ]);
    }
}
