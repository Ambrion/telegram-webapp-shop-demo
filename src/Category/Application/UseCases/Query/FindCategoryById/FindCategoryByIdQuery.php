<?php

declare(strict_types=1);

namespace App\Category\Application\UseCases\Query\FindCategoryById;

use App\Shared\Application\Query\QueryInterface;

readonly class FindCategoryByIdQuery implements QueryInterface
{
    public function __construct(public int $id)
    {
    }
}
