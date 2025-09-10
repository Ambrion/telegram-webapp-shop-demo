<?php

declare(strict_types=1);

namespace App\Order\Application\UseCases\Query\FindOrderById;

use App\Shared\Application\Query\QueryInterface;

class FindOrderByIdQuery implements QueryInterface
{
    public function __construct(public int $id)
    {
    }
}
