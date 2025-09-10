<?php

declare(strict_types=1);

namespace App\Telegram\Infrastructure\Adapter;

use App\Users\Domain\DTO\UserDTO;
use App\Users\Infrastructure\Api\UserApiInterface;

readonly class UserAdapter implements UserAdapterInterface
{
    public function __construct(private UserApiInterface $api)
    {
    }

    /**
     * Find user by telegram chat id.
     */
    public function findUserByTelegramId(int $telegramId): ?UserDTO
    {
        return $this->api->findUserByTelegramId($telegramId);
    }
}
