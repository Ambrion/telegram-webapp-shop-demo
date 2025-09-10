<?php

declare(strict_types=1);

namespace App\Users\Infrastructure\Service;

use App\Shared\Application\Command\CommandBusInterface;
use App\Users\Application\Command\CreateUser\CreateUserCommand;
use App\Users\Application\Service\UserCommandServiceInterface;

readonly class UserCommandService implements UserCommandServiceInterface
{
    public function __construct(private CommandBusInterface $commandBus)
    {
    }

    public function createUser(string $email, ?string $password, int $telegramId, ?string $telegramUsername): string
    {
        $command = new CreateUserCommand($email, $password, $telegramId, $telegramUsername);

        return $this->commandBus->execute($command);
    }
}
