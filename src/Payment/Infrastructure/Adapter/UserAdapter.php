<?php

declare(strict_types=1);

namespace App\Payment\Infrastructure\Adapter;

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
