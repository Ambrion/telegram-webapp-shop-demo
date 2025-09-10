<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\Form;

use App\Product\Domain\DTO\ProductDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Название',
                'translation_domain' => 'admin.product',
                'attr' => ['maxlength' => 255],
                'required' => true,
                'help' => 'Заполните название товара',
            ])
            ->add('description', TextareaType::class, [
                'attr' => ['class' => 'tinymce'],
                'label' => 'Описание',
                'translation_domain' => 'admin.product',
                'required' => false,
                'help' => 'Заполните описание товара',
            ])
            ->add('price', IntegerType::class, [
                'label' => 'Цена',
                'translation_domain' => 'admin.product',
                'required' => true,
                'help' => 'Заполните цену товара',
            ])
            ->add('categories', ChoiceType::class, [
                'label' => 'Категории',
                'translation_domain' => 'admin.product',
                'mapped' => false,
                'choices' => $options['category_choices'],
                'choice_label' => 'title',
                'choice_value' => function ($choice) {
                    return $choice ? $choice->id : '';
                },
                'multiple' => true,
                'required' => true,
                'empty_data' => null,
                'help' => 'Выберите категории товара',
                'placeholder' => '-- Выбор категории --',
                'attr' => [
                    'class' => 'selectpicker',
                    'data-live-search' => true,
                ],
            ])
            ->add('isActive', ChoiceType::class, [
                'choices' => [
                    'Опубликован' => 1,
                    'Не опубликован' => 0,
                ],
                'label' => 'Статус',
                'translation_domain' => 'admin.product',
            ])
            ->add('images', FileType::class, [
                'label' => 'Изображения',
                'translation_domain' => 'admin.product',
                'multiple' => true,
                'mapped' => false,
                'required' => false,
                'help' => 'Выберите изображения товара',
                'attr' => [
                    'accept' => 'image/*',
                ],
            ]);

        // Add event listener to handle parentId category
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();

            $categories = $form->get('categories')->getData();
            $data->categories = array_map(
                fn ($category) => is_object($category) && property_exists($category, 'id') ? $category->id : $category,
                $categories
            );

            // Handle image uploads
            $images = $form->get('images')->getData();

            if ($images && $data instanceof ProductDTO) {
                // Store uploaded files in ProductDTO for processing in controller
                $data->images = $images;
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductDTO::class,
            'csrf_protection' => true,
            'category_choices' => [],
        ]);

        $resolver->setAllowedTypes('category_choices', 'array');
    }
}
