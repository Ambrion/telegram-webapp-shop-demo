<?php

declare(strict_types=1);

namespace App\Product\Application\Command\DeleteProductImage;

use App\Product\Domain\Repository\ProductImageCommandRepositoryInterface;
use App\Shared\Application\Command\CommandHandlerInterface;

readonly class DeleteProductImageCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private ProductImageCommandRepositoryInterface $repository,
    ) {
    }

    public function __invoke(DeleteProductImageCommand $productCommand): int
    {
        return $this->repository->delete($productCommand->productId, $productCommand->imageIds);
    }
}
