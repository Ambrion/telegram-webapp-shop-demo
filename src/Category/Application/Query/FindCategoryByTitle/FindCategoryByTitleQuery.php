<?php

declare(strict_types=1);

namespace App\Category\Application\Query\FindCategoryByTitle;

use App\Shared\Application\Query\QueryInterface;

class FindCategoryByTitleQuery implements QueryInterface
{
    public function __construct(public string $title)
    {
    }
}
