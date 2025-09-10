<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\Adapter\Category;

use App\Category\Infrastructure\Filter\CategoryFilter;
use Symfony\Component\Form\FormInterface;

interface CategoryFilterAdapterInterface
{
    public function createFilterForm(CategoryFilter $filter): FormInterface;

    public function handleRequest(FormInterface $form, mixed $request): void;

    public function createCategoryFilter(): CategoryFilter;
}
