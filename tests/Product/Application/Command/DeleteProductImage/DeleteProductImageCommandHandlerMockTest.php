<?php

declare(strict_types=1);

namespace App\Tests\Product\Application\Command\DeleteProductImage;

use App\Product\Application\Command\DeleteProductImage\DeleteProductImageCommand;
use App\Product\Application\Command\DeleteProductImage\DeleteProductImageCommandHandler;
use App\Product\Domain\Repository\ProductImageCommandRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DeleteProductImageCommandHandlerMockTest extends TestCase
{
    private ProductImageCommandRepositoryInterface&MockObject $repositoryMock;

    private DeleteProductImageCommandHandler $handler;

    protected function setUp(): void
    {
        $this->repositoryMock = $this->createMock(ProductImageCommandRepositoryInterface::class);
        $this->handler = new DeleteProductImageCommandHandler($this->repositoryMock);
    }

    public function test_invoke_should_delete_product_images(): void
    {
        // Arrange
        $productId = 1;
        $imageIds = [1, 2, 3];
        $command = new DeleteProductImageCommand($productId, $imageIds);

        $expectedResult = 3; // Number of deleted images
        $this->repositoryMock
            ->expects($this->once())
            ->method('delete')
            ->with($productId, $imageIds)
            ->willReturn($expectedResult);

        // Act
        $result = ($this->handler)($command);

        // Assert
        $this->assertEquals($expectedResult, $result);
    }

    public function test_invoke_should_return_zero_when_no_images_deleted(): void
    {
        // Arrange
        $productId = 999; // Non-existent product
        $imageIds = [999]; // Non-existent images
        $command = new DeleteProductImageCommand($productId, $imageIds);

        $expectedResult = 0;
        $this->repositoryMock
            ->expects($this->once())
            ->method('delete')
            ->with($productId, $imageIds)
            ->willReturn($expectedResult);

        // Act
        $result = ($this->handler)($command);

        // Assert
        $this->assertEquals($expectedResult, $result);
    }
}
