<?php

declare(strict_types=1);

namespace App\Tests\Category\Application\Command\CreateCategory;

use App\Category\Application\UseCases\Command\CreateCategory\CreateCategoryCommand;
use App\Category\Application\UseCases\Command\CreateCategory\CreateCategoryCommandHandler;
use App\Category\Domain\Entity\Category;
use App\Category\Domain\Factory\CategoryFactory;
use App\Category\Domain\Repository\CategoryCommandRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CreateCategoryCommandHandlerMockTest extends TestCase
{
    private CategoryCommandRepositoryInterface&MockObject $categoryRepository;
    private CategoryFactory&MockObject $categoryFactory;
    private CreateCategoryCommandHandler $handler;

    protected function setUp(): void
    {
        $this->categoryRepository = $this->createMock(CategoryCommandRepositoryInterface::class);
        $this->categoryFactory = $this->createMock(CategoryFactory::class);
        $this->handler = new CreateCategoryCommandHandler(
            $this->categoryRepository,
            $this->categoryFactory,
        );
    }

    public function test_invoke_should_create_and_add_category(): void
    {
        // Arrange
        $command = new CreateCategoryCommand(
            title: 'Electronics',
            isActive: true,
            parentId: 1,
            sortOrder: 10
        );

        $category = $this->createMock(Category::class);
        $expectedId = 5;

        $this->categoryFactory->expects($this->once())
            ->method('create')
            ->with(
                $this->isNull(),
                $command->title,
                $command->isActive,
                $command->parentId,
                $command->sortOrder
            )
            ->willReturn($category);

        $this->categoryRepository->expects($this->once())
            ->method('add')
            ->with($category)
            ->willReturn($expectedId);

        // Act
        $result = ($this->handler)($command);

        // Assert
        $this->assertEquals($expectedId, $result);
    }

    public function test_invoke_should_create_category_without_parent(): void
    {
        // Arrange
        $command = new CreateCategoryCommand(
            title: 'Books',
            isActive: false,
            parentId: null,
            sortOrder: 5
        );

        $category = $this->createMock(Category::class);
        $expectedId = 10;

        $this->categoryFactory->expects($this->once())
            ->method('create')
            ->with(
                $this->isNull(),
                $command->title,
                $command->isActive,
                $command->parentId,
                $command->sortOrder
            )
            ->willReturn($category);

        $this->categoryRepository->expects($this->once())
            ->method('add')
            ->with($category)
            ->willReturn($expectedId);

        // Act
        $result = ($this->handler)($command);

        // Assert
        $this->assertEquals($expectedId, $result);
    }

    public function test_invoke_should_return_zero_when_repository_returns_zero(): void
    {
        // Arrange
        $command = new CreateCategoryCommand(
            'Test Category',
            5,
            false,
            10
        );

        $category = $this->createMock(Category::class);

        $this->categoryFactory
            ->expects($this->once())
            ->method('create')
            ->willReturn($category);

        $this->categoryRepository
            ->expects($this->once())
            ->method('add')
            ->with($category)
            ->willReturn(0);

        // Act
        $result = ($this->handler)($command);

        // Assert
        $this->assertIsInt($result);
    }
}
