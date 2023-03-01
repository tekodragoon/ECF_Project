<?php

namespace App\Form;

use App\Entity\GalleryImage;
use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\UX\Dropzone\Form\DropzoneType;

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
            ->add('file', DropzoneType::class, [
                'label' => false,
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Glisser un fichier ou cliquer pour ouvrir une fenêtre de selection.',
                ],
//                'constraints' => [
//                    new File([
//                        'maxSize' => '2048k',
//                        'mimeTypes' => [
//                            'image/jpeg'
//                        ],
//                        'mimeTypesMessage' => 'Fichier .jpeg uniquement.'
//                    ])
//                ]
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom'
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
                'required' => false,
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
