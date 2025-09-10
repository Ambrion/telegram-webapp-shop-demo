<?php

declare(strict_types=1);

namespace App\Product\Application\Query\FindProductImagesByIds;

use App\Product\Domain\Repository\ProductImageQueryRepositoryInterface;
use App\Shared\Application\Query\QueryHandlerInterface;
use App\Shared\Domain\Validation\QueryValidationIdsInterface;

readonly class FindProductImagesByIdsQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private ProductImageQueryRepositoryInterface $repository,
        private QueryValidationIdsInterface $queryValidationIds,
    ) {
    }

    /**
     * @return array<mixed>|null
     */
    public function __invoke(FindProductImagesByIdsQuery $query): ?array
    {
        $this->queryValidationIds->validateArrayIds($query->ids);

        $images = $this->repository->findProductFilePathByIds($query->ids);

        if (!$images) {
            return null;
        }

        return $images;
    }
}
