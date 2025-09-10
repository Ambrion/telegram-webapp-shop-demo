<?php

declare(strict_types=1);

namespace App\Product\Domain\Repository;

use Doctrine\DBAL\Exception;

interface ProductImageCommandRepositoryInterface
{
    /**
     * Add images.
     *
     * @param array<string> $images
     *
     * @throws Exception
     * @throws \Exception
     */
    public function add(int $productId, array $images): int;

    /**
     * Delete product image.
     *
     * @param array<int> $imageIds
     *
     * @throws Exception
     */
    public function delete(int $productId, array $imageIds): int;

    /**
     * Reorder product images.
     *
     * @param array<int> $imageIds
     *
     * @throws Exception
     */
    public function reorder(int $productId, array $imageIds): int;
}
