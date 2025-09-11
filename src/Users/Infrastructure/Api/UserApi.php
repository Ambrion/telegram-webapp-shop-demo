<?php

declare(strict_types=1);

namespace App\Users\Infrastructure\Api;

use App\Users\Domain\DTO\UserDTO;
use App\Users\Domain\Service\UserCommandServiceInterface;
use App\Users\Domain\Service\UserQueryServiceInterface;

readonly class UserApi implements UserApiInterface
{
    public function __construct(
        private UserQueryServiceInterface $userQueryService,
        private UserCommandServiceInterface $userCommandService,
    ) {
    }

    public function findUserByTelegramId(int $telegramId): ?UserDTO
    {
        return $this->userQueryService->findUserByTelegramId($telegramId);
    }

    public function createUser(string $email, ?string $password, int $telegramId, ?string $telegramUsername): string
    {
        return $this->userCommandService->createUser($email, $password, $telegramId, $telegramUsername);
    }

    public function findUserByUlid(string $ulid): ?UserDTO
    {
        return $this->userQueryService->findUserByUlId($ulid);
    }
}
