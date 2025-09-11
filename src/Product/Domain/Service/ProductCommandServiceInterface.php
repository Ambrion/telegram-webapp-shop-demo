<?php

declare(strict_types=1);

namespace App\Product\Domain\Service;

use App\Product\Domain\DTO\ProductDTO;

interface ProductCommandServiceInterface
{
    /**
     * Create product.
     *
     * @return int product id
     */
    public function createProduct(ProductDTO $productDTO): int;

    /**
     * Update product.
     */
    public function updateProduct(ProductDTO $productDTO): int;

    /**
     * Create product images.
     *
     * @param array<string> $images
     */
    public function createProductImage(int $productId, array $images): int;

    /**
     * Delete product images.
     *
     * @param array<int> $imageIds
     */
    public function deleteProductImage(int $productId, array $imageIds): int;

    /**
     * Delete product image file.
     *
     * @param array<string> $imageFilePath
     */
    public function deleteProductImageFile(array $imageFilePath, string $storagePath): int;

    /**
     * Reorder product images.
     *
     * @param array<int> $imageIds
     */
    public function reorderProductImage(int $productId, array $imageIds): int;

    /**
     * Create empty product DTO.
     */
    public function createEmptyProductDTO(): ?ProductDTO;
}
