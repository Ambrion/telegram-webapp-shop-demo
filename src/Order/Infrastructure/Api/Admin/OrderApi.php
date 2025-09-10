<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Api\Admin;

use App\Order\Domain\DTO\OrderDTO;
use App\Order\Domain\DTO\OrderListDTO;
use App\Order\Domain\Filter\OrderFilterInterface;
use App\Order\Domain\Service\OrderQueryServiceInterface;

readonly class OrderApi implements OrderApiInterface
{
    public function __construct(
        private OrderQueryServiceInterface $orderQueryService,
    ) {
    }

    /**
     * Find order by id.
     */
    public function findOrderByIdQuery(int $id): ?OrderDTO
    {
        return $this->orderQueryService->findOrderByIdQuery($id);
    }

    public function countAllOrderByFilter(OrderFilterInterface $filter): int
    {
        return $this->orderQueryService->countAllOrderByFilter($filter);
    }

    /**
     * List order with pagination.
     *
     * @return array<OrderListDTO>|null
     */
    public function listOrderWithPagination(OrderFilterInterface $filter, int $offset, int $limit): ?array
    {
        return $this->orderQueryService->listOrderWithPagination($filter, $offset, $limit);
    }
}
