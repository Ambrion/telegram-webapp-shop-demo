<?php

declare(strict_types=1);

namespace App\Product\Application\UseCases\Command\ReorderProductImage;

use App\Product\Domain\Repository\ProductImageCommandRepositoryInterface;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Validation\QueryValidationIdsInterface;

readonly class ReorderProductImageCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private ProductImageCommandRepositoryInterface $repository,
        private QueryValidationIdsInterface $queryValidationIds,
    ) {
    }

    public function __invoke(ReorderProductImageCommand $productCommand): int
    {
        $this->queryValidationIds->validateArrayIds($productCommand->imageIds);

        return $this->repository->reorder($productCommand->productId, $productCommand->imageIds);
    }
}
