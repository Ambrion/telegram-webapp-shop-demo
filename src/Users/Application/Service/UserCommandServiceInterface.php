<?php

declare(strict_types=1);

namespace App\Users\Application\Service;

interface UserCommandServiceInterface
{
    public function createUser(string $email, ?string $password, int $telegramId, ?string $telegramUsername): ?string;
}
