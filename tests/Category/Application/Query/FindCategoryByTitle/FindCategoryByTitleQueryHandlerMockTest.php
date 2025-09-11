<?php

declare(strict_types=1);

namespace App\Tests\Category\Application\Query\FindCategoryByTitle;

use App\Category\Application\UseCases\Query\FindCategoryByTitle\FindCategoryByTitleQuery;
use App\Category\Application\UseCases\Query\FindCategoryByTitle\FindCategoryByTitleQueryHandler;
use App\Category\Domain\DTO\CategoryDTO;
use App\Category\Domain\Repository\CategoryQueryRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FindCategoryByTitleQueryHandlerMockTest extends TestCase
{
    private CategoryQueryRepositoryInterface&MockObject $repository;

    private FindCategoryByTitleQueryHandler $handler;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(CategoryQueryRepositoryInterface::class);
        $this->handler = new FindCategoryByTitleQueryHandler($this->repository);
    }

    public function test_invoke_should_return_category_dt_o_when_category_exists(): void
    {
        $title = 'My new category with title';
        $slug = 'my-new-category-with-title';

        // Arrange
        $query = new FindCategoryByTitleQuery($title);

        $repositoryResult = [
            'id' => 1,
            'title' => $title,
            'slug' => $slug,
            'parent_id' => null,
            'is_active' => 1,
            'sort_order' => 10,
        ];

        $this->repository->expects($this->once())
            ->method('findOneByTitle')
            ->with($query->title)
            ->willReturn($repositoryResult);

        // Act
        $result = ($this->handler)($query);

        // Assert
        $this->assertInstanceOf(CategoryDTO::class, $result);
        $this->assertEquals(1, $result->id);
        $this->assertEquals($title, $result->title);
        $this->assertEquals($slug, $result->slug);
        $this->assertNull($result->parentId);
        $this->assertTrue($result->isActive);
        $this->assertEquals(10, $result->sortOrder);
    }

    public function test_invoke_should_return_null_when_category_does_not_exist(): void
    {
        // Arrange
        $query = new FindCategoryByTitleQuery('title');

        $this->repository->expects($this->once())
            ->method('findOneByTitle')
            ->with($query->title)
            ->willReturn([]);

        // Act
        $result = ($this->handler)($query);

        // Assert
        $this->assertNull($result);
    }
}
