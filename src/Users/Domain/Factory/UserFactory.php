<?php

declare(strict_types=1);

namespace App\Users\Domain\Factory;

use App\Shared\Domain\Security\Role;
use App\Users\Domain\Entity\User;
use App\Users\Domain\Service\UserPasswordHasherInterface;
use App\Users\Domain\ValueObject\TelegramIdValue;
use App\Users\Domain\ValueObject\TelegramUsernameValue;

readonly class UserFactory
{
    public function __construct(public UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function create(
        string $email,
        ?string $password,
        string $role = Role::ROLE_USER,
        ?int $telegramId = null,
        ?string $telegramUserName = null,
    ): User {
        $user = new User($email, $role);

        if (empty($password)) {
            $password = uniqid('password_');
        }

        $user->setPassword($password, $this->passwordHasher);

        if (!is_null($telegramId)) {
            $telegramIdValue = new TelegramIdValue($telegramId);
            $user->setTelegramId($telegramIdValue);
        }

        if (!is_null($telegramUserName)) {
            $telegramUserNameValue = new TelegramUsernameValue($telegramUserName);
            $user->setTelegramUserName($telegramUserNameValue);
        }

        return $user;
    }
}
