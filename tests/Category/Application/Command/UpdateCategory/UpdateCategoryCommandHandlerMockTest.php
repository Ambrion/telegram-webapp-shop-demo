<?php

declare(strict_types=1);

namespace App\Tests\Category\Application\Command\UpdateCategory;

use App\Category\Application\UseCases\Command\UpdateCategory\UpdateCategoryCommand;
use App\Category\Application\UseCases\Command\UpdateCategory\UpdateCategoryCommandHandler;
use App\Category\Domain\Entity\Category;
use App\Category\Domain\Factory\CategoryFactory;
use App\Category\Domain\Repository\CategoryCommandRepositoryInterface;
use Doctrine\DBAL\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UpdateCategoryCommandHandlerMockTest extends TestCase
{
    private CategoryCommandRepositoryInterface&MockObject $categoryRepository;

    private CategoryFactory&MockObject $categoryFactory;

    private UpdateCategoryCommandHandler $handler;

    protected function setUp(): void
    {
        $this->categoryRepository = $this->createMock(CategoryCommandRepositoryInterface::class);
        $this->categoryFactory = $this->createMock(CategoryFactory::class);

        $this->handler = new UpdateCategoryCommandHandler(
            $this->categoryRepository,
            $this->categoryFactory,
        );
    }

    public function test_invoke_should_update_category(): void
    {
        // Arrange
        $command = new UpdateCategoryCommand(
            id: 1,
            title: 'Test Category',
            parentId: null,
            isActive: true,
            sortOrder: 10
        );

        $category = $this->createMock(Category::class);

        $this->categoryFactory->expects($this->once())
            ->method('create')
            ->with(
                $command->id,
                $command->title,
                $command->isActive,
                $command->parentId,
                $command->sortOrder
            )
            ->willReturn($category);

        $this->categoryRepository->expects($this->once())
            ->method('update')
            ->with($category)
            ->willReturn(1);

        // Act
        $result = ($this->handler)($command);

        // Assert
        $this->assertEquals(1, $result);
    }

    public function test_invoke_should_handle_exception(): void
    {
        // Arrange
        $command = new UpdateCategoryCommand(
            id: 1,
            title: 'Test Category',
            parentId: null,
            isActive: true,
            sortOrder: 10
        );

        $category = $this->createMock(Category::class);

        $this->categoryFactory->expects($this->once())
            ->method('create')
            ->willReturn($category);

        $this->categoryRepository->expects($this->once())
            ->method('update')
            ->with($category)
            ->willThrowException(new Exception('Database error'));

        // Assert
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Database error');

        // Act
        ($this->handler)($command);
    }
}
