<?php

declare(strict_types=1);

namespace App\Product\Application\Query\FindProductById;

use App\Shared\Application\Query\QueryInterface;

class FindProductByIdQuery implements QueryInterface
{
    public function __construct(public int $id)
    {
    }
}
