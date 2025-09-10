<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\Service;

use App\Product\Application\Command\CreateProduct\CreateProductCommand;
use App\Product\Application\Command\CreateProductImage\CreateProductImageCommand;
use App\Product\Application\Command\DeleteProductImage\DeleteProductImageCommand;
use App\Product\Application\Command\DeleteProductImageFile\DeleteProductImageFileCommand;
use App\Product\Application\Command\ReorderProductImage\ReorderProductImageCommand;
use App\Product\Application\Command\UpdateProduct\UpdateProductCommand;
use App\Product\Application\Service\ProductCommandServiceInterface;
use App\Product\Domain\DTO\ProductDTO;
use App\Shared\Infrastructure\Bus\CommandBus;

readonly class ProductCommandService implements ProductCommandServiceInterface
{
    public function __construct(private CommandBus $commandBus)
    {
    }

    /**
     * Create product.
     *
     * @return int product id
     */
    public function createProduct(ProductDTO $productDTO): int
    {
        $command = new CreateProductCommand(
            $productDTO->title,
            $productDTO->description,
            $productDTO->price,
            $productDTO->isActive,
            $productDTO->categories,
            $productDTO->images
        );

        return $this->commandBus->execute($command);
    }

    /**
     * Update product.
     */
    public function updateProduct(ProductDTO $productDTO): int
    {
        $command = new UpdateProductCommand(
            $productDTO->id,
            $productDTO->title,
            $productDTO->description,
            $productDTO->price,
            $productDTO->isActive,
            $productDTO->categories,
            $productDTO->images
        );

        return $this->commandBus->execute($command);
    }

    /**
     * Create product images.
     *
     * @param array<string> $images
     */
    public function createProductImage(int $productId, array $images): int
    {
        $command = new CreateProductImageCommand($productId, $images);

        return $this->commandBus->execute($command);
    }

    /**
     * Delete product images.
     *
     * @param array<int> $imageIds
     */
    public function deleteProductImage(int $productId, array $imageIds): int
    {
        $command = new DeleteProductImageCommand($productId, $imageIds);

        return $this->commandBus->execute($command);
    }

    /**
     * Delete product image file.
     *
     * @param array<string> $imageFilePath
     */
    public function deleteProductImageFile(array $imageFilePath, string $storagePath): int
    {
        $command = new DeleteProductImageFileCommand($imageFilePath, $storagePath);

        return $this->commandBus->execute($command);
    }

    /**
     * Reorder product images.
     *
     * @param array<int> $imageIds
     */
    public function reorderProductImage(int $productId, array $imageIds): int
    {
        $command = new ReorderProductImageCommand($productId, $imageIds);

        return $this->commandBus->execute($command);
    }

    /**
     * Create empty product DTO.
     */
    public function createEmptyProductDTO(): ?ProductDTO
    {
        return new ProductDTO(null, null, null, null, null, false);
    }
}
