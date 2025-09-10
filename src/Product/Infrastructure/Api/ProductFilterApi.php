<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\Api;

use App\Product\Infrastructure\Filter\ProductFilter;
use App\Product\Infrastructure\Form\ProductFilterForm;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

readonly class ProductFilterApi implements ProductFilterApiInterface
{
    public function __construct(
        private FormFactoryInterface $formFactory,
    ) {
    }

    public function createFilterForm(ProductFilter $filter): FormInterface
    {
        return $this->formFactory->create(ProductFilterForm::class, $filter);
    }

    public function handleRequest(FormInterface $form, mixed $request): void
    {
        $form->handleRequest($request);
    }

    public function createProductFilter(): ProductFilter
    {
        return new ProductFilter();
    }
}
