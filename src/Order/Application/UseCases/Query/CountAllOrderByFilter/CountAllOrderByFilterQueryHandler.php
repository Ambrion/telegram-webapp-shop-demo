<?php

declare(strict_types=1);

namespace App\Order\Application\UseCases\Query\CountAllOrderByFilter;

use App\Order\Domain\Repository\OrderQueryRepositoryInterface;
use App\Shared\Application\Query\QueryHandlerInterface;

readonly class CountAllOrderByFilterQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private OrderQueryRepositoryInterface $repository,
    ) {
    }

    public function __invoke(CountAllOrderByFilterQuery $query): int
    {
        return $this->repository->countAll($query->filter);
    }
}
