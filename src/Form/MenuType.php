<?php

namespace App\Form;

use App\Entity\Menu;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MenuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('description', TextType::class, [
                'label' => 'Description',
            ])
            ->add('compositions', CollectionType::class, [
                'entry_type' => CompositionType::class,
                'by_reference' => false,
                'entry_options' => [
                    'label' => false
                ],
                'allow_add' => true,
                'allow_delete' => true,
            ])
            ->add('price', NumberType::class, [
                'label' => 'Prix'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Menu::class,
        ]);
    }
}
