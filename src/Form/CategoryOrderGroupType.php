<?php

namespace App\Form;

use App\Model\CategoryGroup;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryOrderGroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('categories', CollectionType::class, [
                'entry_type' => CategoryOrderType::class,
                'by_reference' => false,
                'entry_options' => [
                    'label' => false
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CategoryGroup::class,
        ]);
    }
}
