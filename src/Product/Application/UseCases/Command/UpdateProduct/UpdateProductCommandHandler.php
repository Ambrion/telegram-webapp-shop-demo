<?php

declare(strict_types=1);

namespace App\Product\Application\UseCases\Command\UpdateProduct;

use App\Product\Domain\Factory\ProductFactory;
use App\Product\Domain\Repository\ProductCommandRepositoryInterface;
use App\Product\Domain\Service\HandleProductImageUploadInterface;
use App\Shared\Application\Command\CommandHandlerInterface;

readonly class UpdateProductCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private ProductCommandRepositoryInterface $productRepository,
        private ProductFactory $productFactory,
        private HandleProductImageUploadInterface $handleImageUpload,
    ) {
    }

    /**
     * @throws \Exception
     */
    public function __invoke(UpdateProductCommand $updateCommand): int
    {
        $product = $this->productFactory->create(
            $updateCommand->id,
            $updateCommand->title,
            $updateCommand->description,
            $updateCommand->price,
            $updateCommand->isActive,
            $updateCommand->categories,
            $updateCommand->images
        );

        if (!empty($updateCommand->images)) {
            $this->handleImageUpload->handle($product->getId()->getValue(), $product->getImages()->getElements());
        }

        return $this->productRepository->update($product);
    }
}
