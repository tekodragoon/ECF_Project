<?php

namespace App\Form;

use App\Entity\GalleryImage;
use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GalleryImageType extends AbstractType
{
    private array $recipes;

    public function __construct(RecipeRepository $repository)
    {
        $this->recipes = $repository->findAll();
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('path', TextType::class, [
                'label' => 'Chemin complet'
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('visible', CheckboxType::class, [
                'label' => 'Visible'
            ])
        ;
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            $recipes = [];
            foreach ($this->recipes as $recipe) {
                $recipes[] = $recipe;
            }

            $form->add('recipe', EntityType::class, [
                'label' => 'Recette',
                'class' => Recipe::class,
                'placeholder' => '',
                'choices' => $recipes
            ]);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GalleryImage::class,
        ]);
    }
}
