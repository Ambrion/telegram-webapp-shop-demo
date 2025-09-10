<?php

declare(strict_types=1);

namespace App\Category\Domain\Service;

class CategoryStatus
{
    public const int CATEGORY_STATUS_UNPUBLISHED = 0;
    public const int CATEGORY_STATUS_PUBLISHED = 1;

    public const array CATEGORY_STATUS = [
        self::CATEGORY_STATUS_UNPUBLISHED => 'category_unpublished',
        self::CATEGORY_STATUS_PUBLISHED => 'category_published',
    ];
}
