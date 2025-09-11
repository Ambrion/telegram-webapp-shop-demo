<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\Adapter\Product;

use App\Category\Domain\DTO\CategoryDTO;
use App\Product\Domain\DTO\ProductDTO;
use App\Product\Infrastructure\Api\ProductFormApiInterface;
use Symfony\Component\Form\FormInterface;

readonly class ProductFormAdapter implements ProductFormAdapterInterface
{
    public function __construct(private ProductFormApiInterface $api)
    {
    }

    /**
     * @param array<CategoryDTO> $categoryChoices
     */
    public function createForm(ProductDTO $ProductDTO, array $categoryChoices): FormInterface
    {
        return $this->api->createForm($ProductDTO, $categoryChoices);
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
