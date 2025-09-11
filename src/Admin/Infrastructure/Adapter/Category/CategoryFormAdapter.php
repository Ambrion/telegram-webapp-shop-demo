<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\Adapter\Category;

use App\Category\Domain\DTO\CategoryDTO;
use App\Category\Infrastructure\Api\Admin\CategoryFormApiInterface;
use Symfony\Component\Form\FormInterface;

readonly class CategoryFormAdapter implements CategoryFormAdapterInterface
{
    public function __construct(private CategoryFormApiInterface $api)
    {
    }

    /**
     * @param array<CategoryDTO> $categoryChoices
     */
    public function createForm(CategoryDTO $categoryDTO, array $categoryChoices): FormInterface
    {
        return $this->api->createForm($categoryDTO, $categoryChoices);
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
