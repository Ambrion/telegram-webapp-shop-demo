<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\Adapter\Category;

use App\Category\Domain\DTO\CategoryDTO;
use Symfony\Component\Form\FormInterface;

interface CategoryFormAdapterInterface
{
    /**
     * @param array<CategoryDTO> $categoryChoices
     */
    public function createForm(CategoryDTO $categoryDTO, array $categoryChoices): FormInterface;

    public function handleRequest(FormInterface $form, mixed $request): void;

    public function isSubmitted(FormInterface $form): bool;

    public function isValid(FormInterface $form): bool;
}
