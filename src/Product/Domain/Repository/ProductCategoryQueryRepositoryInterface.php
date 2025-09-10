<?php

declare(strict_types=1);

namespace App\Product\Domain\Repository;

use Doctrine\DBAL\Exception;

interface ProductCategoryQueryRepositoryInterface
{
    /**
     * List product category ids.
     *
     * @return array<int>|null
     *
     * @throws Exception
     */
    public function listProductCategoryIds(int $productId): ?array;
}
