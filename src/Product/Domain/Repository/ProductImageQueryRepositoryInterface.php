<?php

declare(strict_types=1);

namespace App\Product\Domain\Repository;

interface ProductImageQueryRepositoryInterface
{
    /**
     * Find product images.
     *
     * @return array<string, mixed>|null
     */
    public function findProductImage(int $productId): ?array;

    /**
     * List product file path by ids.
     *
     * @param array<int> $ids
     *
     * @return array<mixed>|null
     */
    public function findProductFilePathByIds(array $ids): ?array;

    /**
     * Find product images by product ids grouped by product_id.
     *
     * @param array<int> $ids
     *
     * @return array<string, mixed>|null
     */
    public function findProductImageByProductIds(array $ids): ?array;
}
