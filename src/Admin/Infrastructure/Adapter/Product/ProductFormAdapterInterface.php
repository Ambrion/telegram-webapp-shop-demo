<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\Adapter\Product;

use App\Category\Application\DTO\CategoryDTO;
use App\Product\Domain\DTO\ProductDTO;
use Symfony\Component\Form\FormInterface;

interface ProductFormAdapterInterface
{
    /**
     * @param array<CategoryDTO> $categoryChoices
     */
    public function createForm(ProductDTO $ProductDTO, array $categoryChoices): FormInterface;

    public function handleRequest(FormInterface $form, mixed $request): void;

    public function isSubmitted(FormInterface $form): bool;

    public function isValid(FormInterface $form): bool;
}
