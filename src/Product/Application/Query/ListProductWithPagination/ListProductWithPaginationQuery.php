<?php

declare(strict_types=1);

namespace App\Product\Application\Query\ListProductWithPagination;

use App\Product\Domain\Filter\ProductFilterInterface;
use App\Shared\Application\Query\QueryInterface;

readonly class ListProductWithPaginationQuery implements QueryInterface
{
    public function __construct(
        public ProductFilterInterface $filter,
        public int $offset,
        public int $limit,
    ) {
    }
}
