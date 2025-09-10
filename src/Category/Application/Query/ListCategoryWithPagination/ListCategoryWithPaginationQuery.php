<?php

declare(strict_types=1);

namespace App\Category\Application\Query\ListCategoryWithPagination;

use App\Category\Domain\Filter\CategoryFilterInterface;
use App\Shared\Application\Query\QueryInterface;

readonly class ListCategoryWithPaginationQuery implements QueryInterface
{
    public function __construct(
        public CategoryFilterInterface $filter,
        public int $offset,
        public int $limit,
    ) {
    }
}
