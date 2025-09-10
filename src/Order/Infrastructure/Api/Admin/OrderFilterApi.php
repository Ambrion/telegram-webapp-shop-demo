<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Api\Admin;

use App\Order\Infrastructure\Filter\OrderFilter;
use App\Order\Infrastructure\Form\OrderFilterForm;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

readonly class OrderFilterApi implements OrderFilterApiInterface
{
    public function __construct(
        private FormFactoryInterface $formFactory,
    ) {
    }

    public function createFilterForm(OrderFilter $filter): FormInterface
    {
        return $this->formFactory->create(OrderFilterForm::class, $filter);
    }

    public function handleRequest(FormInterface $form, mixed $request): void
    {
        $form->handleRequest($request);
    }

    public function createOrderFilter(): OrderFilter
    {
        return new OrderFilter();
    }
}
