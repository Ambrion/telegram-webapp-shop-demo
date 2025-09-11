<?php

declare(strict_types=1);

namespace App\Users\Application\UseCases\Query\FindUserByTelegramId;

use App\Shared\Application\Query\QueryInterface;

class FindUserByTelegramIdQuery implements QueryInterface
{
    public function __construct(public int $telegramId)
    {
    }
}
