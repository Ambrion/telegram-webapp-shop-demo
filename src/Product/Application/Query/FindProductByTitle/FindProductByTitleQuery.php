<?php

declare(strict_types=1);

namespace App\Product\Application\Query\FindProductByTitle;

use App\Shared\Application\Query\QueryInterface;

class FindProductByTitleQuery implements QueryInterface
{
    public function __construct(public string $title)
    {
    }
}
