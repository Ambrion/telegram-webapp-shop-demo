<?php

declare(strict_types=1);

namespace App\Users\Infrastructure\Service;

use App\Users\Domain\Entity\User;
use App\Users\Domain\Service\UserPasswordHasherInterface;

class UserPasswordHasher implements UserPasswordHasherInterface
{
    public function hash(User $user, string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}
