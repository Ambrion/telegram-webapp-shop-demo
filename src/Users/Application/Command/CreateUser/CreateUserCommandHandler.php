<?php

declare(strict_types=1);

namespace App\Users\Application\Command\CreateUser;

use App\Shared\Application\Command\CommandHandlerInterface;
use App\Users\Domain\Factory\UserFactory;
use App\Users\Domain\Repository\UserCommandRepositoryInterface;

readonly class CreateUserCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private UserCommandRepositoryInterface $repository,
        private UserFactory $userFactory,
    ) {
    }

    /**
     * @return string UserId
     */
    public function __invoke(CreateUserCommand $createUserCommand): string
    {
        $user = $this->userFactory->create(
            email: $createUserCommand->email,
            password: $createUserCommand->password,
            telegramId: $createUserCommand->telegramId,
            telegramUserName: $createUserCommand->telegramUserName
        );
        $this->repository->add($user);

        return $user->getUlid();
    }
}
