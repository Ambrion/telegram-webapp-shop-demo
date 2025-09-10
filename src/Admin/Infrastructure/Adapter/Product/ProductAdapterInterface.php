<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\Adapter\Product;

use App\Product\Domain\DTO\ProductDTO;
use App\Product\Domain\DTO\ProductListDTO;
use App\Product\Domain\Filter\ProductFilterInterface;

interface ProductAdapterInterface
{
    /**
     * Find product by id.
     */
    public function findProductById(int $id): ?ProductDTO;

    /**
     * Find product by title.
     */
    public function findProductByTitle(string $title): ?ProductDTO;

    /**
     * Find product images by ids.
     *
     * @param array<int> $ids
     *
     * @return array<mixed>|null
     */
    public function findProductImagesByIds(array $ids): ?array;

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

    /**
     * Count all products by filter.
     */
    public function countAllProductByFilter(ProductFilterInterface $filter): int;

    /**
     * List products with pagination.
     *
     * @return array<ProductListDTO>|null
     */
    public function listProductWithPagination(ProductFilterInterface $filter, int $offset, int $limit): ?array;
}
