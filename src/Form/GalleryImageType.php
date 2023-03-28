<?php

namespace App\Form;

use App\Entity\GalleryImage;
use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Contracts\Translation\TranslatorInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;

class GalleryImageType extends AbstractType implements EventSubscriberInterface
{
    private array $recipes;
    private TranslatorInterface $translator;

    public function __construct(RecipeRepository $repository, TranslatorInterface $translator)
    {
        $this->recipes = $repository->findAll();
        $this->translator = $translator;
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
                        'mimeTypesMessage' => $this->translator->trans('message.jpegOnly'),
                    ])
                ]
            ])
            ->add('name', TextType::class, [
                'label' => 'picture.name',
                'required' => false,
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
        $builder->addEventSubscriber($this);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::SUBMIT => 'ensureOneFieldIsSubmitted',
        ];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GalleryImage::class,
        ]);
    }

    public function ensureOneFieldIsSubmitted(FormEvent $event)
    {
        $submittedData = $event->getData();

        if ($submittedData->getName() === null && $submittedData->getRecipe() === null) {
            throw new TransformationFailedException(
                'Either a name or a recipe must be provided.',
                0,
                null,
                $this->translator->trans('message.inputNoName'),
                [],
            );
        }
    }
}
