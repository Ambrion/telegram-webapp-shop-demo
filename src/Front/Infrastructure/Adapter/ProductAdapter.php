<?php

declare(strict_types=1);

namespace App\Front\Infrastructure\Adapter;

use App\Product\Domain\DTO\ProductCategoryDTO;
use App\Product\Infrastructure\Api\ProductApiInterface;

readonly class ProductAdapter implements ProductAdapterInterface
{
    public function __construct(private ProductApiInterface $api)
    {
    }

    public function findAllProductWithCategory(): ?ProductCategoryDTO
    {
        return $this->api->findAllProductWithCategory();
    }
}
