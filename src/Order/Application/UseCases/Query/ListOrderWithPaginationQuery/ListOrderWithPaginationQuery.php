<?php

declare(strict_types=1);

namespace App\Order\Application\UseCases\Query\ListOrderWithPaginationQuery;

use App\Order\Domain\Filter\OrderFilterInterface;
use App\Shared\Application\Query\QueryInterface;

class ListOrderWithPaginationQuery implements QueryInterface
{
    public function __construct(
        public OrderFilterInterface $filter,
        public int $offset,
        public int $limit,
    ) {
    }
}
