<?php

namespace App\Form;

use App\Entity\Guest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GuestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Firstname',
                'required' => false,
            ])
            ->add('adult', ChoiceType::class, [
                'label' => 'Adult',
                'choices' => [
                    'Adult' => true,
                    'Child' => false,
                ],
                'expanded' => false,
                'multiple' => false,
            ])
            ->add('allergies', CollectionType::class, [
                'entry_type' => AllergyType::class,
                'by_reference' => false,
                'entry_options' => [
                    'label' => false
                ],
                'allow_add' => true,
                'allow_delete' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Guest::class,
        ]);
    }
}
