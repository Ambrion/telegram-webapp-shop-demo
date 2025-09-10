<?php

declare(strict_types=1);

namespace App\Product\Domain\Service;

class ProductStatus
{
    public const int PRODUCT_STATUS_UNPUBLISHED = 0;
    public const int PRODUCT_STATUS_PUBLISHED = 1;

    public const array PRODUCT_STATUS = [
        self::PRODUCT_STATUS_UNPUBLISHED => 'product_unpublished',
        self::PRODUCT_STATUS_PUBLISHED => 'product_published',
    ];
}
