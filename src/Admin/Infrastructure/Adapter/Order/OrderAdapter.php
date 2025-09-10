<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\Adapter\Order;

use App\Order\Domain\DTO\OrderDTO;
use App\Order\Domain\DTO\OrderListDTO;
use App\Order\Domain\Filter\OrderFilterInterface;
use App\Order\Infrastructure\Api\Admin\OrderApiInterface;

readonly class OrderAdapter implements OrderAdapterInterface
{
    public function __construct(private OrderApiInterface $api)
    {
    }

    /**
     * Find order by id.
     */
    public function findOrderByIdQuery(int $id): ?OrderDTO
    {
        return $this->api->findOrderByIdQuery($id);
    }

    /**
     * Count all orders by filter.
     */
    public function countAllOrderByFilter(OrderFilterInterface $filter): int
    {
        return $this->api->countAllOrderByFilter($filter);
    }

    /**
     * List order with pagination.
     *
     * @return array<OrderListDTO>|null
     */
    public function listOrderWithPagination(OrderFilterInterface $filter, int $offset, int $limit): ?array
    {
        return $this->api->listOrderWithPagination($filter, $offset, $limit);
    }
}
