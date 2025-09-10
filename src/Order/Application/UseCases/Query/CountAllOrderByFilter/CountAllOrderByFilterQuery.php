<?php

declare(strict_types=1);

namespace App\Order\Application\UseCases\Query\CountAllOrderByFilter;

use App\Order\Domain\Filter\OrderFilterInterface;
use App\Shared\Application\Query\QueryInterface;

class CountAllOrderByFilterQuery implements QueryInterface
{
    public function __construct(public OrderFilterInterface $filter)
    {
    }
}
