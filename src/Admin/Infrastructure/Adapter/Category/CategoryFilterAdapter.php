<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\Adapter\Category;

use App\Category\Infrastructure\Api\Admin\CategoryFilterApiInterface;
use App\Category\Infrastructure\Filter\CategoryFilter;
use Symfony\Component\Form\FormInterface;

readonly class CategoryFilterAdapter implements CategoryFilterAdapterInterface
{
    public function __construct(private CategoryFilterApiInterface $api)
    {
    }

    public function createFilterForm(CategoryFilter $filter): FormInterface
    {
        return $this->api->createFilterForm($filter);
    }

    public function handleRequest(FormInterface $form, mixed $request): void
    {
        $form->handleRequest($request);
    }

    public function createCategoryFilter(): CategoryFilter
    {
        return $this->api->createCategoryFilter();
    }
}
