<?php

declare(strict_types=1);

namespace App\Order\Domain\Service;

use App\Order\Domain\DTO\OrderDTO;
use App\Order\Domain\DTO\OrderListDTO;
use App\Order\Domain\Filter\OrderFilterInterface;

interface OrderQueryServiceInterface
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
     * List orders with pagination.
     *
     * @return array<OrderListDTO>|null
     */
    public function listOrderWithPagination(OrderFilterInterface $filter, int $offset, int $limit): ?array;
}
