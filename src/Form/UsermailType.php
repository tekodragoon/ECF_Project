<?php

namespace App\Form;

use App\Entity\Mailing;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UsermailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('usermail', EmailType::class, [
                'label' => 'Subscribe to our newsletter and stay informed of upcoming events.',
                'attr' => [
                    'placeholder' => 'Your email...'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Mailing::class,
        ]);
    }
}
