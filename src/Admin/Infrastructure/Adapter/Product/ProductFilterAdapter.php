<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\Adapter\Product;

use App\Product\Infrastructure\Api\ProductFilterApiInterface;
use App\Product\Infrastructure\Filter\ProductFilter;
use Symfony\Component\Form\FormInterface;

readonly class ProductFilterAdapter implements ProductFilterAdapterInterface
{
    public function __construct(private ProductFilterApiInterface $api)
    {
    }

    public function createFilterForm(ProductFilter $filter): FormInterface
    {
        return $this->api->createFilterForm($filter);
    }

    public function handleRequest(FormInterface $form, mixed $request): void
    {
        $form->handleRequest($request);
    }

    public function createProductFilter(): ProductFilter
    {
        return $this->api->createProductFilter();
    }
}
