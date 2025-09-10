<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Adapter;

use App\Order\Domain\DTO\OrderTelegramUserDTO;
use App\Shared\Domain\ValueObject\GlobalUserUlid;

interface UserAdapterInterface
{
    /**
     * Find user by telegram chat id.
     */
    public function findUserByTelegramId(int $telegramId): ?OrderTelegramUserDTO;

    /**
     * Create user.
     */
    public function createUser(string $email, ?string $password, int $telegramId, ?string $telegramUsername): ?GlobalUserUlid;
}
