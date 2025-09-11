<?php

declare(strict_types=1);

namespace App\Users\Application\UseCases\Query\FindUserByTelegramId;

use App\Shared\Application\Query\QueryHandlerInterface;
use App\Users\Domain\DTO\UserDTO;
use App\Users\Domain\Repository\UserQueryRepositoryInterface;

readonly class FindUserByTelegramIdQueryHandler implements QueryHandlerInterface
{
    public function __construct(private UserQueryRepositoryInterface $repository)
    {
    }

    public function __invoke(FindUserByTelegramIdQuery $query): ?UserDTO
    {
        $result = $this->repository->findUserByTelegramId($query->telegramId);

        if (empty($result)) {
            return null;
        }

        $roles = json_decode($result['roles'], true);

        return new UserDTO(
            ulid: $result['ulid'],
            email: $result['email'],
            roles: $roles,
            telegramId: $result['telegram_id'],
            telegramUserName: $result['telegram_username'],
        );
    }
}
