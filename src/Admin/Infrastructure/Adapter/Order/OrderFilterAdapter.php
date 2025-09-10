<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\Adapter\Order;

use App\Order\Infrastructure\Api\Admin\OrderFilterApiInterface;
use App\Order\Infrastructure\Filter\OrderFilter;
use Symfony\Component\Form\FormInterface;

readonly class OrderFilterAdapter implements OrderFilterAdapterInterface
{
    public function __construct(private OrderFilterApiInterface $api)
    {
    }

    public function createFilterForm(OrderFilter $filter): FormInterface
    {
        return $this->api->createFilterForm($filter);
    }

    public function handleRequest(FormInterface $form, mixed $request): void
    {
        $form->handleRequest($request);
    }

    public function createOrderFilter(): OrderFilter
    {
        return $this->api->createOrderFilter();
    }
}
