<?php

declare(strict_types=1);

namespace App\Tests\Product\Application\UseCases\Command\CreateProductImage;

use App\Product\Application\UseCases\Command\CreateProductImage\CreateProductImageCommand;
use App\Product\Application\UseCases\Command\CreateProductImage\CreateProductImageCommandHandler;
use App\Product\Domain\Repository\ProductImageCommandRepositoryInterface;
use PHPUnit\Framework\TestCase;

class CreateProductImageCommandHandlerMockTest extends TestCase
{
    public function test_invoke_should_add_product_image(): void
    {
        // Arrange
        $productId = 1;
        $images = ['image1.jpg', 'image2.jpg'];

        $command = $this->createMock(CreateProductImageCommand::class);
        $command->productId = $productId;
        $command->images = $images;

        $repository = $this->createMock(ProductImageCommandRepositoryInterface::class);
        $repository->expects($this->once())
            ->method('add')
            ->with($productId, $images)
            ->willReturn(1);

        $handler = new CreateProductImageCommandHandler($repository);

        // Act
        $result = $handler($command);

        // Assert
        $this->assertEquals(1, $result);
    }
}
