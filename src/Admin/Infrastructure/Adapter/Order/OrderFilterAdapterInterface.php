<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\Adapter\Order;

use App\Order\Infrastructure\Filter\OrderFilter;
use Symfony\Component\Form\FormInterface;

interface OrderFilterAdapterInterface
{
    public function createFilterForm(OrderFilter $filter): FormInterface;

    public function handleRequest(FormInterface $form, mixed $request): void;

    public function createOrderFilter(): OrderFilter;
}
