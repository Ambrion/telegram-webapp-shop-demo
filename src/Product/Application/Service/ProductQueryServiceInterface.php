<?php

declare(strict_types=1);

namespace App\Product\Application\Service;

use App\Product\Domain\DTO\ProductCategoryDTO;
use App\Product\Domain\DTO\ProductDTO;
use App\Product\Domain\DTO\ProductForOrderDTO;
use App\Product\Domain\DTO\ProductListDTO;
use App\Product\Domain\Filter\ProductFilterInterface;
use App\Shared\Domain\DTO\OrderProductDTO;

interface ProductQueryServiceInterface
{
    /**
     * Find cart products by ids.
     *
     * @param array<int> $ids
     *
     * @return ProductForOrderDTO[]|null
     */
    public function findCartProductByIds(array $ids): ?array;

    /**
     * Create products from cart.
     *
     * @param array<string, mixed> $cart
     *
     * @return OrderProductDTO[]|null
     */
    public function createProductFromCart(array $cart): ?array;

    /**
     * Find all products with category.
     */
    public function findAllProductWithCategory(): ?ProductCategoryDTO;

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
