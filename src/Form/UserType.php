<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastname', TextType::class, [
                'label' => 'account.lastname',
                'constraints' => [
                    new NotBlank([
                        'message' => 'forms.user.lastname'
                    ])
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => 'account.firstname',
                'constraints' => [
                    new NotBlank([
                        'message' => 'forms.user.firstname'
                    ])
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'account.email',
                'constraints' => [
                    new Email([
                        'message' => 'forms.user.email'
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
