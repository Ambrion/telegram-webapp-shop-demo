<?php

declare(strict_types=1);

namespace App\Order\Domain\Repository;

use App\Order\Domain\Filter\OrderFilterInterface;

interface OrderQueryRepositoryInterface
{
    /**
     * List order with filter and pagination.
     *
     * @return array<array<string, mixed>>
     */
    public function listOrderWithPagination(OrderFilterInterface $orderFilter, int $offset, int $limit): array;

    /**
     * Count all order with filter.
     */
    public function countAll(OrderFilterInterface $orderFilter): int;

    /**
     * Find one order by id.
     *
     * @return array<string, mixed>
     */
    public function findOneById(int $id): array;

    /**
     * Find order products.
     *
     * @return array<array<string, mixed>>
     */
    public function findOrderProduct(int $orderId): array;
}
