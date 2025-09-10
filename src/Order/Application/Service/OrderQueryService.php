<?php

declare(strict_types=1);

namespace App\Order\Application\Service;

use App\Order\Application\UseCases\Query\CountAllOrderByFilter\CountAllOrderByFilterQuery;
use App\Order\Application\UseCases\Query\FindOrderById\FindOrderByIdQuery;
use App\Order\Application\UseCases\Query\ListOrderWithPaginationQuery\ListOrderWithPaginationQuery;
use App\Order\Domain\DTO\OrderDTO;
use App\Order\Domain\DTO\OrderListDTO;
use App\Order\Domain\Filter\OrderFilterInterface;
use App\Order\Domain\Service\OrderQueryServiceInterface;
use App\Shared\Application\Query\QueryBusInterface;

readonly class OrderQueryService implements OrderQueryServiceInterface
{
    public function __construct(
        private QueryBusInterface $queryBus,
    ) {
    }

    /**
     * Find order by id.
     */
    public function findOrderByIdQuery(int $id): ?OrderDTO
    {
        $query = new FindOrderByIdQuery($id);

        return $this->queryBus->execute($query);
    }

    /**
     * Count all orders by filter.
     */
    public function countAllOrderByFilter(OrderFilterInterface $filter): int
    {
        $query = new CountAllOrderByFilterQuery($filter);

        return $this->queryBus->execute($query);
    }

    /**
     * List orders with pagination.
     *
     * @return array<OrderListDTO>|null
     */
    public function listOrderWithPagination(OrderFilterInterface $filter, int $offset, int $limit): ?array
    {
        $query = new ListOrderWithPaginationQuery(
            filter: $filter,
            offset: $offset,
            limit: $limit
        );

        return $this->queryBus->execute($query);
    }
}
