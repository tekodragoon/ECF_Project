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
                'label' => 'Inscrivez vous à notre newsletter et restez informés des prochains évènements',
                'attr' => [
                    'placeholder' => 'Votre email...'
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
