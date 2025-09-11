<?php

declare(strict_types=1);

namespace App\Category\Application\UseCases\Query\FindAllByCriteria;

use App\Category\Domain\Filter\CategoryFilterInterface;
use App\Shared\Application\Query\QueryInterface;

readonly class FindAllByCriteriaQuery implements QueryInterface
{
    public function __construct(
        public CategoryFilterInterface $filter,
    ) {
    }
}
