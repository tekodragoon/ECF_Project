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
    // TODO: Add translated labels
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
