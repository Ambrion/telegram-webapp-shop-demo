<?php

declare(strict_types=1);

namespace App\Telegram\Infrastructure\Adapter;

use App\Users\Domain\DTO\UserDTO;

interface UserAdapterInterface
{
    /**
     * Find user by telegram chat id.
     */
    public function findUserByTelegramId(int $telegramId): ?UserDTO;
}
