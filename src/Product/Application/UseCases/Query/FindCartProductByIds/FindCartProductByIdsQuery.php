<?php

declare(strict_types=1);

namespace App\Product\Application\UseCases\Query\FindCartProductByIds;

use App\Shared\Application\Query\QueryInterface;

class FindCartProductByIdsQuery implements QueryInterface
{
    /**
     * @param array<int> $ids
     */
    public function __construct(public array $ids)
    {
    }
}
