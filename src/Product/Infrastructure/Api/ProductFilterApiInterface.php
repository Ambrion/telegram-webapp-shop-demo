<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\Api;

use App\Product\Infrastructure\Filter\ProductFilter;
use Symfony\Component\Form\FormInterface;

interface ProductFilterApiInterface
{
    public function createFilterForm(ProductFilter $filter): FormInterface;

    public function handleRequest(FormInterface $form, mixed $request): void;

    public function createProductFilter(): ProductFilter;
}
