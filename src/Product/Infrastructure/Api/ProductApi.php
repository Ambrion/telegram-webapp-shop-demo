<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\Api;

use App\Product\Domain\Service\ProductCommandServiceInterface;
use App\Product\Domain\Service\ProductQueryServiceInterface;
use App\Product\Domain\DTO\ProductCategoryDTO;
use App\Product\Domain\DTO\ProductDTO;
use App\Product\Domain\DTO\ProductForOrderDTO;
use App\Product\Domain\DTO\ProductListDTO;
use App\Product\Domain\Filter\ProductFilterInterface;
use App\Shared\Domain\DTO\OrderProductDTO;

readonly class ProductApi implements ProductApiInterface
{
    public function __construct(
        private ProductCommandServiceInterface $productCommandService,
        private ProductQueryServiceInterface $productQueryService,
    ) {
    }

    /**
     * Find cart products by ids.
     *
     * @param array<int> $ids
     *
     * @return ProductForOrderDTO[]|null
     */
    public function findCartProductByIds(array $ids): ?array
    {
        return $this->productQueryService->findCartProductByIds($ids);
    }

    /**
     * Create products from cart.
     *
     * @param array<string, mixed> $cart
     *
     * @return OrderProductDTO[]|null
     */
    public function createProductFromCart(array $cart): ?array
    {
        return $this->productQueryService->createProductFromCart($cart);
    }

    /**
     * Find all products with category.
     */
    public function findAllProductWithCategory(): ?ProductCategoryDTO
    {
        return $this->productQueryService->findAllProductWithCategory();
    }

    /**
     * Find product by id.
     */
    public function findProductById(int $id): ?ProductDTO
    {
        return $this->productQueryService->findProductById($id);
    }

    /**
     * Find product by title.
     */
    public function findProductByTitle(string $title): ?ProductDTO
    {
        return $this->productQueryService->findProductByTitle($title);
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
        return $this->productQueryService->findProductImagesByIds($ids);
    }

    /**
     * Create product.
     *
     * @return int product id
     */
    public function createProduct(ProductDTO $productDTO): int
    {
        return $this->productCommandService->createProduct($productDTO);
    }

    /**
     * Update product.
     */
    public function updateProduct(ProductDTO $productDTO): int
    {
        return $this->productCommandService->updateProduct($productDTO);
    }

    /**
     * Delete product images.
     *
     * @param array<int> $imageIds
     */
    public function deleteProductImage(int $productId, array $imageIds): int
    {
        return $this->productCommandService->deleteProductImage($productId, $imageIds);
    }

    /**
     * Delete product image file.
     *
     * @param array<string> $imageFilePath
     */
    public function deleteProductImageFile(array $imageFilePath, string $storagePath): int
    {
        return $this->productCommandService->deleteProductImageFile($imageFilePath, $storagePath);
    }

    /**
     * Reorder product images.
     *
     * @param array<int> $imageIds
     */
    public function reorderProductImage(int $productId, array $imageIds): int
    {
        return $this->productCommandService->reorderProductImage($productId, $imageIds);
    }

    /**
     * Create empty product DTO.
     */
    public function createEmptyProductDTO(): ?ProductDTO
    {
        return $this->productCommandService->createEmptyProductDTO();
    }

    /**
     * Count all products by filter.
     */
    public function countAllProductByFilter(ProductFilterInterface $filter): int
    {
        return $this->productQueryService->countAllProductByFilter($filter);
    }

    /**
     * List products with pagination.
     *
     * @return array<ProductListDTO>|null
     */
    public function listProductWithPagination(ProductFilterInterface $filter, int $offset, int $limit): ?array
    {
        return $this->productQueryService->listProductWithPagination($filter, $offset, $limit);
    }
}
