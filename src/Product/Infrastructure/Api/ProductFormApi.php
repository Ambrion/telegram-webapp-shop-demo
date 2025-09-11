<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\Api;

use App\Category\Domain\DTO\CategoryDTO;
use App\Product\Domain\DTO\ProductDTO;
use App\Product\Infrastructure\Form\ProductForm;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

readonly class ProductFormApi implements ProductFormApiInterface
{
    public function __construct(
        private FormFactoryInterface $formFactory,
    ) {
    }

    /**
     * @param array<CategoryDTO> $categoryChoices
     */
    public function createForm(ProductDTO $productDTO, array $categoryChoices): FormInterface
    {
        return $this->formFactory->create(ProductForm::class, $productDTO, [
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
