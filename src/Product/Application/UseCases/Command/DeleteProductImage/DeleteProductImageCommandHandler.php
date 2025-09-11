<?php

declare(strict_types=1);

namespace App\Product\Application\UseCases\Command\DeleteProductImage;

use App\Product\Domain\Repository\ProductImageCommandRepositoryInterface;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Validation\QueryValidationIdsInterface;

readonly class DeleteProductImageCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private ProductImageCommandRepositoryInterface $repository,
        private QueryValidationIdsInterface $queryValidationIds,
    ) {
    }

    public function __invoke(DeleteProductImageCommand $productCommand): int
    {
        $this->queryValidationIds->validateArrayIds($productCommand->imageIds);

        return $this->repository->delete($productCommand->productId, $productCommand->imageIds);
    }
}
