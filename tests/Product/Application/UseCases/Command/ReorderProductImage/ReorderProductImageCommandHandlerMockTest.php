<?php

declare(strict_types=1);

namespace App\Tests\Product\Application\UseCases\Command\ReorderProductImage;

use App\Product\Application\UseCases\Command\ReorderProductImage\ReorderProductImageCommand;
use App\Product\Application\UseCases\Command\ReorderProductImage\ReorderProductImageCommandHandler;
use App\Product\Domain\Repository\ProductImageCommandRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ReorderProductImageCommandHandlerMockTest extends TestCase
{
    private ProductImageCommandRepositoryInterface&MockObject $repositoryMock;

    private ReorderProductImageCommandHandler $handler;

    protected function setUp(): void
    {
        $this->repositoryMock = $this->createMock(ProductImageCommandRepositoryInterface::class);
        $this->handler = new ReorderProductImageCommandHandler($this->repositoryMock);
    }

    public function test_reorder_product_images(): void
    {
        // Arrange
        $productId = 1;
        $imageIds = [3, 1, 2];
        $expectedResult = 3;

        $command = new ReorderProductImageCommand($productId, $imageIds);

        $this->repositoryMock
            ->expects($this->once())
            ->method('reorder')
            ->with($productId, $imageIds)
            ->willReturn($expectedResult);

        // Act
        $result = ($this->handler)($command);

        // Assert
        $this->assertEquals($expectedResult, $result);
    }

    public function test_reorder_with_empty_image_ids(): void
    {
        // Arrange
        $productId = 5;
        $imageIds = [];
        $expectedResult = 0;

        $command = new ReorderProductImageCommand($productId, $imageIds);

        $this->repositoryMock
            ->expects($this->once())
            ->method('reorder')
            ->with($productId, $imageIds)
            ->willReturn($expectedResult);

        // Act
        $result = ($this->handler)($command);

        // Assert
        $this->assertEquals($expectedResult, $result);
    }
}
