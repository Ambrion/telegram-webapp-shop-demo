<?php

declare(strict_types=1);

namespace App\Shared\Domain\Security;

class Role
{
    public const string ROLE_USER = 'ROLE_USER';
    public const string ROLE_ADMIN = 'ROLE_ADMIN';

    public const array ROLES = [
        self::ROLE_USER,
        self::ROLE_ADMIN,
    ];
}
