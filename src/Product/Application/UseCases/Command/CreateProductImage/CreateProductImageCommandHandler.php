<?php

declare(strict_types=1);

namespace App\Product\Application\UseCases\Command\CreateProductImage;

use App\Product\Domain\Repository\ProductImageCommandRepositoryInterface;
use App\Shared\Application\Command\CommandHandlerInterface;

readonly class CreateProductImageCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private ProductImageCommandRepositoryInterface $repository,
    ) {
    }

    public function __invoke(CreateProductImageCommand $productCommand): int
    {
        return $this->repository->add($productCommand->productId, $productCommand->images);
    }
}
