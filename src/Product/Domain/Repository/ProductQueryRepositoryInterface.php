<?php

declare(strict_types=1);

namespace App\Product\Domain\Repository;

use App\Product\Domain\Filter\ProductFilterInterface;

interface ProductQueryRepositoryInterface
{
    /**
     * List products with filter and pagination.
     *
     * @return array<array<string, mixed>>
     */
    public function listProductWithPagination(ProductFilterInterface $productFilter, int $offset, int $limit): array;

    /**
     * Count all product.
     */
    public function countAll(ProductFilterInterface $productFilter): int;

    /**
     * Find by id.
     *
     * @return array<string, mixed>
     */
    public function findById(int $id): array;

    /**
     * Find one by title.
     *
     * @return array<string, mixed>
     */
    public function findOneByTitle(string $title): array;

    /**
     * Find all active products by ids.
     *
     * @param array<int> $ids
     *
     * @return array<array<string, mixed>>
     */
    public function findAllActiveForOrderById(array $ids): array;

    /**
     * Find all product with category.
     *
     * @return array<array<string, mixed>>
     */
    public function findAllProductCategory(): array;
}
