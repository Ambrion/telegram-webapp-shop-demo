<?php

declare(strict_types=1);

namespace App\Payment\Infrastructure\Adapter;

use App\Users\Domain\DTO\UserDTO;

interface UserAdapterInterface
{
    /**
     * Find user by ULID.
     */
    public function findUserByUlid(string $ulid): ?UserDTO;
}
