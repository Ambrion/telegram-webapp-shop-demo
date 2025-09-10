<?php

declare(strict_types=1);

namespace App\Category\Infrastructure\Api\Admin;

use App\Category\Infrastructure\Filter\CategoryFilter;
use App\Category\Infrastructure\Form\CategoryFilterForm;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

readonly class CategoryFilterApi implements CategoryFilterApiInterface
{
    public function __construct(
        private FormFactoryInterface $formFactory,
    ) {
    }

    public function createFilterForm(CategoryFilter $filter): FormInterface
    {
        return $this->formFactory->create(CategoryFilterForm::class, $filter);
    }

    public function handleRequest(FormInterface $form, mixed $request): void
    {
        $form->handleRequest($request);
    }

    public function createCategoryFilter(): CategoryFilter
    {
        return new CategoryFilter();
    }
}
