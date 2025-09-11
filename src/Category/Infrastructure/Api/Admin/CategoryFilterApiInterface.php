<?php

declare(strict_types=1);

namespace App\Category\Infrastructure\Api\Admin;

use App\Category\Domain\Filter\CategoryFilterInterface;
use App\Category\Infrastructure\Filter\CategoryFilter;
use Symfony\Component\Form\FormInterface;

interface CategoryFilterApiInterface
{
    public function createFilterForm(CategoryFilterInterface $filter): FormInterface;

    public function handleRequest(FormInterface $form, mixed $request): void;

    public function createCategoryFilter(): CategoryFilter;
}
