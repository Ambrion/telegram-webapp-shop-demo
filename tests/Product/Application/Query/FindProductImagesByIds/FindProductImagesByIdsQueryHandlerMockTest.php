<?php

declare(strict_types=1);

namespace App\Tests\Product\Application\Query\FindProductImagesByIds;

use App\Product\Application\UseCases\Query\FindProductImagesByIds\FindProductImagesByIdsQuery;
use App\Product\Application\UseCases\Query\FindProductImagesByIds\FindProductImagesByIdsQueryHandler;
use App\Product\Domain\Repository\ProductImageQueryRepositoryInterface;
use App\Shared\Domain\Validation\QueryValidationIdsInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FindProductImagesByIdsQueryHandlerMockTest extends TestCase
{
    private ProductImageQueryRepositoryInterface&MockObject $repositoryMock;

    private FindProductImagesByIdsQueryHandler $handler;

    protected function setUp(): void
    {
        $this->repositoryMock = $this->createMock(ProductImageQueryRepositoryInterface::class);
        $queryValidationIdsMock = $this->createMock(QueryValidationIdsInterface::class);
        $this->handler = new FindProductImagesByIdsQueryHandler($this->repositoryMock, $queryValidationIdsMock);
    }

    public function test_find_product_images_by_ids_returns_array_wit_path(): void
    {
        // Arrange
        $imageIds = [1, 2, 3];
        $query = new FindProductImagesByIdsQuery($imageIds);

        $images = [
            ['path' => 'images/product1_1.jpg'],
            ['path' => 'images/product1_2.jpg'],
            ['path' => 'images/product1_3.jpg'],
        ];

        $this->repositoryMock
            ->expects($this->once())
            ->method('findProductFilePathByIds')
            ->with($imageIds)
            ->willReturn($images);

        // Act
        $result = ($this->handler)($query);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($images, $result);
    }

    public function test_find_product_images_by_ids_returns_null_when_no_images_found(): void
    {
        // Arrange
        $imageIds = [999, 1000];
        $query = new FindProductImagesByIdsQuery($imageIds);

        $this->repositoryMock
            ->expects($this->once())
            ->method('findProductFilePathByIds')
            ->with($imageIds)
            ->willReturn(null);

        // Act
        $result = ($this->handler)($query);

        // Assert
        $this->assertNull($result);
    }
}
