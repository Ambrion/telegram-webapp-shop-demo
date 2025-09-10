<?php

declare(strict_types=1);

namespace App\Category\Infrastructure\Form;

use App\Category\Application\DTO\CategoryDTO;
use App\Category\Domain\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Название',
                'translation_domain' => 'admin.category',
                'attr' => ['maxlength' => 255],
                'required' => true,
                'help' => 'Заполните название категории',
            ])
            ->add('parentId', EntityType::class, [
                'label' => 'Родительская категория',
                'translation_domain' => 'admin.category',
                'class' => Category::class,
                'mapped' => false,
                'choices' => $options['category_choices'],
                'choice_label' => 'title',
                'choice_value' => function ($choice) {
                    return $choice ? $choice->id : '';
                },
                'required' => false,
                'empty_data' => null,
                'help' => 'Выберите родительскую категорию',
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
                'translation_domain' => 'admin.category',
            ])
            ->add('sortOrder', IntegerType::class, [
                'label' => 'Порядок отображения',
                'translation_domain' => 'admin.category',
                'required' => true,
                'help' => '0 самое первое отображение',
            ]);

        // Add event listener to handle parentId category
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();

            /** @var CategoryDTO $parentCategory */
            $parentCategory = $form->get('parentId')->getData();

            if ($parentCategory && $data instanceof CategoryDTO) {
                $data->parentId = $parentCategory->id;
            } elseif ($data instanceof CategoryDTO) {
                $data->parentId = null;
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CategoryDTO::class,
            'csrf_protection' => true,
            'category_choices' => [],
        ]);

        $resolver->setAllowedTypes('category_choices', 'array');
    }
}
