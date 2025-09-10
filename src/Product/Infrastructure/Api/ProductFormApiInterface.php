<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\Api;

use App\Category\Application\DTO\CategoryDTO;
use App\Product\Domain\DTO\ProductDTO;
use Symfony\Component\Form\FormInterface;

interface ProductFormApiInterface
{
    /**
     * @param array<CategoryDTO> $categoryChoices
     */
    public function createForm(ProductDTO $productDTO, array $categoryChoices): FormInterface;

    public function handleRequest(FormInterface $form, mixed $request): void;

    public function isSubmitted(FormInterface $form): bool;

    public function isValid(FormInterface $form): bool;
}
