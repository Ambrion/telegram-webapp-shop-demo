<?php

declare(strict_types=1);

namespace App\Product\Application\UseCases\Command\ReorderProductImage;

use App\Product\Domain\Repository\ProductImageCommandRepositoryInterface;
use App\Shared\Application\Command\CommandHandlerInterface;

readonly class ReorderProductImageCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private ProductImageCommandRepositoryInterface $repository,
    ) {
    }

    public function __invoke(ReorderProductImageCommand $productCommand): int
    {
        return $this->repository->reorder($productCommand->productId, $productCommand->imageIds);
    }
}
