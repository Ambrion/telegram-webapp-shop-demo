<?php

declare(strict_types=1);

namespace App\Category\Application\Query\CountAllCategoryByFilter;

use App\Category\Domain\Repository\CategoryQueryRepositoryInterface;
use App\Shared\Application\Query\QueryHandlerInterface;

readonly class CountAllCategoryByFilterQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private CategoryQueryRepositoryInterface $repository,
    ) {
    }

    public function __invoke(CountAllCategoryByFilterQuery $query): int
    {
        return $this->repository->countAll($query->filter);
    }
}
