<?php

namespace App\Form;

use App\Entity\GalleryImage;
use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\UX\Dropzone\Form\DropzoneType;
use Vich\UploaderBundle\Form\Type\VichImageType;

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
            ->add('imageFile', VichImageType::class, [
                'label' => false,
                'mapped' => true,
                'required' => true,
                'allow_delete' => false,
                'download_uri' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg'
                        ],
                        'mimeTypesMessage' => '.jpeg file only.'
                    ])
                ]
            ])
            ->add('name', TextType::class, [
                'label' => 'picture.name'
            ])
        ;
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            $recipes = [];
            foreach ($this->recipes as $recipe) {
                $recipes[] = $recipe;
            }

            $form->add('recipe', EntityType::class, [
                'label' => 'picture.recipe',
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
