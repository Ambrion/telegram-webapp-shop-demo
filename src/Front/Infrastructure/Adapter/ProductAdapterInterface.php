<?php

declare(strict_types=1);

namespace App\Front\Infrastructure\Adapter;

use App\Product\Domain\DTO\ProductCategoryDTO;

interface ProductAdapterInterface
{
    public function findAllProductWithCategory(): ?ProductCategoryDTO;
}
