<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Adapter;

use App\Order\Domain\DTO\OrderTelegramUserDTO;
use App\Shared\Domain\ValueObject\GlobalUserUlid;
use App\Users\Infrastructure\Api\UserApiInterface;

readonly class UserAdapter implements UserAdapterInterface
{
    public function __construct(private UserApiInterface $api)
    {
    }

    public function findUserByTelegramId(int $telegramId): ?OrderTelegramUserDTO
    {
        $user = $this->api->findUserByTelegramId($telegramId);
        if (empty($user)) {
            return null;
        }

        return new OrderTelegramUserDTO(
            ulid: $user->ulid,
            email: $user->email,
            telegramId: $user->telegramId,
            telegramUserName: $user->telegramUserName
        );
    }

    /**
     * Create user.
     */
    public function createUser(string $email, ?string $password, int $telegramId, ?string $telegramUsername): ?GlobalUserUlid
    {
        $ulid = $this->api->createUser($email, $password, $telegramId, $telegramUsername);
        if (empty($ulid)) {
            return null;
        }

        return new GlobalUserUlid($ulid);
    }
}
