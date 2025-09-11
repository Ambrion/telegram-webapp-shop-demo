<?php

declare(strict_types=1);

namespace App\Product\Application\UseCases\Query\CountAllProductByFilter;

use App\Product\Domain\Filter\ProductFilterInterface;
use App\Shared\Application\Query\QueryInterface;

readonly class CountAllProductByFilterQuery implements QueryInterface
{
    public function __construct(public ProductFilterInterface $filter)
    {
    }
}
