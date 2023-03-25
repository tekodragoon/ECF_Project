<?php

namespace App\Form;

use App\Entity\Recipe;
use App\Entity\RecipeCategory;
use App\Repository\RecipeCategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeType extends AbstractType
{
    /**
     * @var RecipeCategory[]
     */
    private array $recipeCategories;

    public function __construct(RecipeCategoryRepository $categoryRepository)
    {
        $this->recipeCategories = $categoryRepository->findAll();
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'recipe.name'
            ])
            ->add('description', TextType::class, [
                'label' => 'recipe.description'
            ])
            ->add('price', NumberType::class, [
                'label' => 'recipe.price'
            ])
        ;
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            $categories = [];
            foreach ($this->recipeCategories as $category) {
                $categories[] = $category;
            }

            $form->add('category', EntityType::class, [
                'label' => 'recipe.category',
                'class' => RecipeCategory::class,
                'placeholder' => '',
                'choices' => $categories
            ]);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
