<?php

declare(strict_types=1);

namespace App\Users\Application\UseCases\Command\CreateUser;

use App\Shared\Application\Command\CommandInterface;

readonly class CreateUserCommand implements CommandInterface
{
    public function __construct(
        public string $email,
        public ?string $password,
        public ?int $telegramId,
        public ?string $telegramUserName,
    ) {
    }
}
