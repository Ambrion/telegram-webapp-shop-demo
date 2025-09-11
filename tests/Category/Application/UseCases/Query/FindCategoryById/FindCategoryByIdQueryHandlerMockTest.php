<?php

declare(strict_types=1);

namespace App\Tests\Category\Application\UseCases\Query\FindCategoryById;

use App\Category\Application\UseCases\Query\FindCategoryById\FindCategoryByIdQuery;
use App\Category\Application\UseCases\Query\FindCategoryById\FindCategoryByIdQueryHandler;
use App\Category\Domain\DTO\CategoryDTO;
use App\Category\Domain\Repository\CategoryQueryRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FindCategoryByIdQueryHandlerMockTest extends TestCase
{
    private CategoryQueryRepositoryInterface&MockObject $repository;

    private FindCategoryByIdQueryHandler $handler;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(CategoryQueryRepositoryInterface::class);
        $this->handler = new FindCategoryByIdQueryHandler($this->repository);
    }

    public function test_invoke_should_return_category_dt_o_when_category_exists(): void
    {
        // Arrange
        $query = new FindCategoryByIdQuery(1);

        $repositoryResult = [
            'id' => 1,
            'title' => 'Test Category',
            'slug' => 'test-category',
            'parent_id' => null,
            'is_active' => 1,
            'sort_order' => 10,
        ];

        $this->repository->expects($this->once())
            ->method('findById')
            ->with($query->id)
            ->willReturn($repositoryResult);

        // Act
        $result = ($this->handler)($query);

        // Assert
        $this->assertInstanceOf(CategoryDTO::class, $result);
        $this->assertEquals(1, $result->id);
        $this->assertEquals('Test Category', $result->title);
        $this->assertEquals('test-category', $result->slug);
        $this->assertNull($result->parentId);
        $this->assertTrue($result->isActive);
        $this->assertEquals(10, $result->sortOrder);
    }

    public function test_invoke_should_return_null_when_category_does_not_exist(): void
    {
        // Arrange
        $query = new FindCategoryByIdQuery(999);

        $this->repository->expects($this->once())
            ->method('findById')
            ->with($query->id)
            ->willReturn([]);

        // Act
        $result = ($this->handler)($query);

        // Assert
        $this->assertNull($result);
    }
}
