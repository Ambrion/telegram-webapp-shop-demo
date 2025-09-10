<?php

declare(strict_types=1);

namespace App\Users\Domain\Repository;

interface UserQueryRepositoryInterface
{
    /**
     * Find user by ulid.
     *
     * @return array<string, mixed>|false
     */
    public function findByUlid(string $ulid): array|false;

    /**
     * Find user by email.
     *
     * @return array<string, mixed>|false
     */
    public function findByEmail(string $email): array|false;

    /**
     * Find user by telegramId.
     *
     * @return array<string, mixed>|false
     */
    public function findUserByTelegramId(int $telegramId): array|false;
}
