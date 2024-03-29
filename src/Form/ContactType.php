<?php

namespace App\Form;

use App\Model\Message;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'contact.name',
                'constraints' => [
                    new NotBlank([
                        'message' => 'forms.contact.name',
                    ])
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'contact.email',
                'constraints' => [
                    new NotBlank([
                        'message' => 'forms.contact.email',
                    ])
                ]
            ])
            ->add('subject', TextType::class, [
                'label' => 'contact.subject',
                'constraints' => [
                    new NotBlank([
                        'message' => 'forms.contact.subject',
                    ])
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => 'contact.message',
                'constraints' => [
                    new NotBlank([
                        'message' => 'forms.contact.message',
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Message::class
        ]);
    }
}
