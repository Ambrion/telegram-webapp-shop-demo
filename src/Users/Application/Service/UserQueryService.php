<?php

declare(strict_types=1);

namespace App\Users\Application\Service;

use App\Shared\Application\Query\QueryBusInterface;
use App\Users\Application\UseCases\Query\FindUserByTelegramId\FindUserByTelegramIdQuery;
use App\Users\Application\UseCases\Query\FindUserByUlId\FindUserByUlIdQuery;
use App\Users\Domain\DTO\UserDTO;
use App\Users\Domain\Service\UserQueryServiceInterface;

readonly class UserQueryService implements UserQueryServiceInterface
{
    public function __construct(
        private QueryBusInterface $queryBus,
    ) {
    }

    public function findUserByTelegramId(int $telegramId): ?UserDTO
    {
        $query = new FindUserByTelegramIdQuery($telegramId);

        return $this->queryBus->execute($query);
    }

    public function findUserByUlId(string $ulid): ?UserDTO
    {
        $query = new FindUserByUlIdQuery($ulid);

        return $this->queryBus->execute($query);
    }
}
