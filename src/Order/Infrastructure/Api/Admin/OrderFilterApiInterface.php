<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Api\Admin;

use App\Order\Infrastructure\Filter\OrderFilter;
use Symfony\Component\Form\FormInterface;

interface OrderFilterApiInterface
{
    public function createFilterForm(OrderFilter $filter): FormInterface;

    public function handleRequest(FormInterface $form, mixed $request): void;

    public function createOrderFilter(): OrderFilter;
}
