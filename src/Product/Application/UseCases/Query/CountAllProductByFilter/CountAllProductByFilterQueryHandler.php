<?php

declare(strict_types=1);

namespace App\Product\Application\UseCases\Query\CountAllProductByFilter;

use App\Product\Domain\Repository\ProductQueryRepositoryInterface;
use App\Shared\Application\Query\QueryHandlerInterface;

readonly class CountAllProductByFilterQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private ProductQueryRepositoryInterface $repository,
    ) {
    }

    public function __invoke(CountAllProductByFilterQuery $query): int
    {
        return $this->repository->countAll($query->filter);
    }
}
