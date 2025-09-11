<?php

declare(strict_types=1);

namespace App\Tests\Users\Application\UseCases\Command\CreateUser;

use App\Shared\Domain\Security\Role;
use App\Users\Application\UseCases\Command\CreateUser\CreateUserCommand;
use App\Users\Application\UseCases\Command\CreateUser\CreateUserCommandHandler;
use App\Users\Domain\Entity\User;
use App\Users\Domain\Factory\UserFactory;
use App\Users\Domain\Repository\UserCommandRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CreateUserCommandHandlerMockTest extends TestCase
{
    private UserCommandRepositoryInterface&MockObject $repository;
    private UserFactory&MockObject $userFactory;
    private CreateUserCommandHandler $handler;
    private User&MockObject $user;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(UserCommandRepositoryInterface::class);
        $this->userFactory = $this->createMock(UserFactory::class);
        $this->user = $this->createMock(User::class);

        $this->handler = new CreateUserCommandHandler(
            $this->repository,
            $this->userFactory
        );
    }

    public function test_invoke_should_create_and_add_user(): void
    {
        // Arrange
        $command = new CreateUserCommand(
            email: 'test@example.com',
            password: 'password123',
            telegramId: 123456789,
            telegramUserName: 'test_user'
        );

        $ulid = '01ARZ3NDEKTSV4RRFFQ69G5FAV';

        $this->userFactory
            ->expects($this->once())
            ->method('create')
            ->with(
                $command->email,
                $command->password,
                Role::ROLE_USER,
                $command->telegramId,
                $command->telegramUserName
            )
            ->willReturn($this->user);

        $this->user
            ->expects($this->once())
            ->method('getUlid')
            ->willReturn($ulid);

        $this->repository
            ->expects($this->once())
            ->method('add')
            ->with($this->user);

        // Act
        $result = ($this->handler)($command);

        // Assert
        $this->assertEquals($ulid, $result);
    }
}
