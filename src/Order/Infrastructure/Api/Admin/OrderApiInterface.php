<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Api\Admin;

use App\Order\Domain\DTO\OrderDTO;
use App\Order\Domain\DTO\OrderListDTO;
use App\Order\Domain\Filter\OrderFilterInterface;

interface OrderApiInterface
{
    /**
     * Find order by id.
     */
    public function findOrderByIdQuery(int $id): ?OrderDTO;

    /**
     * Count all orders by filter.
     */
    public function countAllOrderByFilter(OrderFilterInterface $filter): int;

    /**
     * List order with pagination.
     *
     * @return array<OrderListDTO>|null
     */
    public function listOrderWithPagination(OrderFilterInterface $filter, int $offset, int $limit): ?array;
}
