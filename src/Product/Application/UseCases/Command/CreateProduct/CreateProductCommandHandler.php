<?php

declare(strict_types=1);

namespace App\Product\Application\UseCases\Command\CreateProduct;

use App\Product\Domain\Factory\ProductFactory;
use App\Product\Domain\Repository\ProductCommandRepositoryInterface;
use App\Product\Domain\Service\HandleProductImageUploadInterface;
use App\Product\Domain\ValueObject\IdValue;
use App\Shared\Application\Command\CommandHandlerInterface;

readonly class CreateProductCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private ProductCommandRepositoryInterface $repository,
        private ProductFactory $productFactory,
        private HandleProductImageUploadInterface $handleImageUpload,
    ) {
    }

    public function __invoke(CreateProductCommand $productCommand): int
    {
        $product = $this->productFactory->create(
            null,
            $productCommand->title,
            $productCommand->description,
            $productCommand->price,
            $productCommand->isActive,
            $productCommand->categories,
            $productCommand->images
        );

        $productId = $this->repository->add($product);

        $IdValue = new IdValue($productId);
        $product->setId($IdValue);

        if (!empty($productCommand->images)) {
            $this->handleImageUpload->handle($product->getId()->getValue(), $product->getImages()->getElements());
        }

        return $productId;
    }
}
