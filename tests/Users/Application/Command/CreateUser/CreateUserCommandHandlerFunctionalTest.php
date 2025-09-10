<?php

declare(strict_types=1);

namespace App\Tests\Users\Application\Command\CreateUser;

use App\Tests\Resource\Fixture\User\UserFixture;
use App\Users\Domain\Entity\User;
use App\Users\Domain\Repository\UserQueryRepositoryInterface;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateUserCommandHandlerFunctionalTest extends WebTestCase
{
    private UserQueryRepositoryInterface $repository;
    protected AbstractDatabaseTool $databaseTool;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->getContainer()->get(UserQueryRepositoryInterface::class);
        $this->databaseTool = $this->getContainer()->get(DatabaseToolCollection::class)->get();
    }

    public function test_create_user_successfully(): void
    {
        $executor = $this->databaseTool
            ->loadFixtures([UserFixture::class])
            ->getReferenceRepository();
        $user = $executor->getReference(UserFixture::REFERENCE, User::class);

        // Assert
        $this->assertInstanceOf(User::class, $user, 'Result not instance of User.');
        $this->assertNotEmpty($user->getUlid(), 'ULID cannot by empty.');
        $findByUlid = $this->repository->findByUlid($user->getUlid());

        $this->assertNotEmpty($findByUlid, 'Cannot find user by ULID.');
    }
}
