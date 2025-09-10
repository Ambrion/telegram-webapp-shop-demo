<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\Adapter\User;

use App\Users\Domain\DTO\UserDTO;
use App\Users\Infrastructure\Api\UserApiInterface;

readonly class UserAdapter implements UserAdapterInterface
{
    public function __construct(private UserApiInterface $api)
    {
    }

    public function findUserByUlid(string $ulid): ?UserDTO
    {
        return $this->api->findUserByUlid($ulid);
    }
}
