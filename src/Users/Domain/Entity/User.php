<?php

declare(strict_types=1);

namespace App\Users\Domain\Entity;

use App\Shared\Domain\Security\AuthUserInterface;
use App\Shared\Domain\Security\Role;
use App\Shared\Domain\Service\UlidService;
use App\Users\Domain\Service\UserPasswordHasherInterface;
use App\Users\Domain\ValueObject\TelegramIdValue;
use App\Users\Domain\ValueObject\TelegramUsernameValue;

class User implements AuthUserInterface
{
    private string $ulid;

    private string $email;

    private ?string $password = null;

    /**
     * @var array<string>
     */
    private array $roles = [];

    private ?TelegramIdValue $telegramId = null;

    private ?TelegramUsernameValue $telegramUserName = null;

    public function __construct(string $email, string $role)
    {
        $this->ulid = UlidService::generate();
        $this->email = $email;

        $this->addRole($role);
        // Пользователь всегда должен иметь роль ROLE_USER
        if (Role::ROLE_USER !== $role) {
            $this->addRole(Role::ROLE_USER);
        }
    }

    public function getUlid(): string
    {
        return $this->ulid;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function addRole(string $role): void
    {
        if (!in_array($role, $this->roles)) {
            $this->roles[] = $role;
        }
    }

    public function eraseCredentials(): void
    {
        // Не трогаем чтобы когда кто-то зафлашит нашу сущность и у него будут изменения, например пустой пароль когда
        // мы его тут выставим.
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function setPassword(?string $password, UserPasswordHasherInterface $passwordHasher): void
    {
        if (is_null($password)) {
            $this->password = null;
        }

        $this->password = $passwordHasher->hash($this, $password);
    }

    public function getTelegramId(): ?TelegramIdValue
    {
        return $this->telegramId;
    }

    public function setTelegramId(TelegramIdValue $telegramId): static
    {
        $this->telegramId = $telegramId;

        return $this;
    }

    public function getTelegramUserName(): ?TelegramUsernameValue
    {
        return $this->telegramUserName;
    }

    public function setTelegramUserName(TelegramUsernameValue $telegramUserName): static
    {
        $this->telegramUserName = $telegramUserName;

        return $this;
    }
}
