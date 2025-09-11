<?php

declare(strict_types=1);

namespace App\Users\Application\UseCases\Query\FindUserByUlId;

use App\Shared\Application\Query\QueryInterface;

class FindUserByUlIdQuery implements QueryInterface
{
    public function __construct(public string $ulid)
    {
    }
}
