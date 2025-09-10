<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\Adapter\Product;

use App\Product\Domain\DTO\ProductDTO;
use App\Product\Domain\DTO\ProductListDTO;
use App\Product\Domain\Filter\ProductFilterInterface;
use App\Product\Infrastructure\Api\ProductApiInterface;

readonly class ProductAdapter implements ProductAdapterInterface
{
    public function __construct(private ProductApiInterface $api)
    {
    }

    /**
     * Find product by id.
     */
    public function findProductById(int $id): ?ProductDTO
    {
        return $this->api->findProductById($id);
    }

    /**
     * Find product by title.
     */
    public function findProductByTitle(string $title): ?ProductDTO
    {
        return $this->api->findProductByTitle($title);
    }

    /**
     * Find product images by ids.
     *
     * @param array<int> $ids
     *
     * @return array<mixed>|null
     */
    public function findProductImagesByIds(array $ids): ?array
    {
        return $this->api->findProductImagesByIds($ids);
    }

    /**
     * Create product.
     *
     * @return int product id
     */
    public function createProduct(ProductDTO $productDTO): int
    {
        return $this->api->createProduct($productDTO);
    }

    /**
     * Update product.
     */
    public function updateProduct(ProductDTO $productDTO): int
    {
        return $this->api->updateProduct($productDTO);
    }

    /**
     * Delete product images.
     *
     * @param array<int> $imageIds
     */
    public function deleteProductImage(int $productId, array $imageIds): int
    {
        return $this->api->deleteProductImage($productId, $imageIds);
    }

    /**
     * Delete product image file.
     *
     * @param array<string> $imageFilePath
     */
    public function deleteProductImageFile(array $imageFilePath, string $storagePath): int
    {
        return $this->api->deleteProductImageFile($imageFilePath, $storagePath);
    }

    /**
     * Reorder product images.
     *
     * @param array<int> $imageIds
     */
    public function reorderProductImage(int $productId, array $imageIds): int
    {
        return $this->api->reorderProductImage($productId, $imageIds);
    }

    /**
     * Create empty product DTO.
     */
    public function createEmptyProductDTO(): ?ProductDTO
    {
        return $this->api->createEmptyProductDTO();
    }

    /**
     * Count all products by filter.
     */
    public function countAllProductByFilter(ProductFilterInterface $filter): int
    {
        return $this->api->countAllProductByFilter($filter);
    }

    /**
     * List products with pagination.
     *
     * @return array<ProductListDTO>|null
     */
    public function listProductWithPagination(ProductFilterInterface $filter, int $offset, int $limit): ?array
    {
        return $this->api->listProductWithPagination($filter, $offset, $limit);
    }
}
