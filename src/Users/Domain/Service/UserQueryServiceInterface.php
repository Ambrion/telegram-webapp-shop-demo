<?php

declare(strict_types=1);

namespace App\Users\Domain\Service;

use App\Users\Domain\DTO\UserDTO;

interface UserQueryServiceInterface
{
    public function findUserByTelegramId(int $telegramId): ?UserDTO;

    public function findUserByUlId(string $ulid): ?UserDTO;
}
