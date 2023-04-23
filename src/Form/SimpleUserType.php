<?php

namespace App\Form;

use App\Entity\SimpleUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SimpleUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastname', TextType::class, [
                'label' => 'account.lastname'
            ])
            ->add('firstname', TextType::class, [
                'label' => 'account.firstname'
            ])
            ->add('email', EmailType::class, [
                'label' => 'account.email'
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
            'data_class' => SimpleUser::class,
        ]);
    }
}
