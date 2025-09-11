<?php

declare(strict_types=1);

namespace App\Category\Infrastructure\Api\Admin;

use App\Category\Domain\DTO\CategoryDTO;
use App\Category\Infrastructure\Form\CategoryForm;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

readonly class CategoryFormApi implements CategoryFormApiInterface
{
    public function __construct(
        private FormFactoryInterface $formFactory,
    ) {
    }

    /**
     * @param array<CategoryDTO> $categoryChoices
     */
    public function createForm(CategoryDTO $categoryDTO, array $categoryChoices): FormInterface
    {
        return $this->formFactory->create(CategoryForm::class, $categoryDTO, [
            'category_choices' => $categoryChoices,
        ]);
    }

    public function handleRequest(FormInterface $form, mixed $request): void
    {
        $form->handleRequest($request);
    }

    public function isSubmitted(FormInterface $form): bool
    {
        return $form->isSubmitted();
    }

    public function isValid(FormInterface $form): bool
    {
        return $form->isValid();
    }
}
