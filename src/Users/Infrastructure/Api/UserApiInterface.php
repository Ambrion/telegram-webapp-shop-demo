<?php

declare(strict_types=1);

namespace App\Users\Infrastructure\Api;

use App\Users\Domain\DTO\UserDTO;

interface UserApiInterface
{
    public function findUserByTelegramId(int $telegramId): ?UserDTO;

    public function findUserByUlid(string $ulid): ?UserDTO;

    public function createUser(string $email, ?string $password, int $telegramId, ?string $telegramUsername): string;
}
