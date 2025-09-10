<?php

declare(strict_types=1);

namespace App\Tests\Product\Application\Command\UpdateProduct;

use App\Product\Application\Command\UpdateProduct\UpdateProductCommand;
use App\Product\Application\Command\UpdateProduct\UpdateProductCommandHandler;
use App\Product\Domain\Entity\Product;
use App\Product\Domain\Factory\ProductFactory;
use App\Product\Domain\Repository\ProductCommandRepositoryInterface;
use App\Product\Domain\Service\HandleProductImageUploadInterface;
use App\Product\Domain\ValueObject\IdValue;
use App\Product\Domain\ValueObject\ImagesValue;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UpdateProductCommandHandlerMockTest extends TestCase
{
    private ProductCommandRepositoryInterface&MockObject $productRepository;
    private ProductFactory&MockObject $productFactory;
    private UpdateProductCommandHandler $handler;
    private HandleProductImageUploadInterface $handleImageUpload;

    protected function setUp(): void
    {
        $this->productRepository = $this->createMock(ProductCommandRepositoryInterface::class);
        $this->productFactory = $this->createMock(ProductFactory::class);
        $this->handleImageUpload = $this->createMock(HandleProductImageUploadInterface::class);

        $this->handler = new UpdateProductCommandHandler(
            $this->productRepository,
            $this->productFactory,
            $this->handleImageUpload
        );
    }

    public function test_invoke_should_update_product_with_image(): void
    {
        // Arrange
        $command = new UpdateProductCommand(
            id: 1,
            title: 'Test product',
            description: 'Test description',
            price: 999,
            isActive: true,
            categories: [1, 2],
            images: ['image1.jpg', 'image2.jpg']
        );

        $product = $this->createMock(Product::class);

        // Create proper mock objects for return values
        $idValue = $this->createMock(IdValue::class);
        $idValue->method('getValue')->willReturn(1);

        $imageCollection = $this->createMock(ImagesValue::class);
        $imageCollection->method('getElements')
            ->willReturn(['image1.jpg', 'image2.jpg']);

        // Configure the product mock to return proper values with correct types
        $product->expects($this->once())
            ->method('getId')
            ->willReturn($idValue);

        $product->expects($this->once())
            ->method('getImages')
            ->willReturn($imageCollection);

        $this->productFactory->expects($this->once())
            ->method('create')
            ->with(
                $this->equalTo($command->id),
                $this->equalTo($command->title),
                $this->equalTo($command->description),
                $this->equalTo($command->price),
                $this->equalTo($command->isActive),
                $this->equalTo($command->categories),
                $this->equalTo($command->images)
            )
            ->willReturn($product);

        $this->handleImageUpload
            ->expects($this->once())
            ->method('handle')
            ->with(
                1, // ID value
                ['image1.jpg', 'image2.jpg'] // Images
            );

        $this->productRepository->expects($this->once())
            ->method('update')
            ->with($product)
            ->willReturn(1);

        // Act
        $result = ($this->handler)($command);

        // Assert
        $this->assertEquals(1, $result);
    }

    public function test_invoke_should_not_handle_images_when_no_images(): void
    {
        // Arrange
        $command = new UpdateProductCommand(
            id: 1,
            title: 'Test product',
            description: 'Test description',
            price: 999,
            isActive: true,
            categories: [1, 2],
            images: []
        );

        $product = $this->createMock(Product::class);

        $this->productFactory->expects($this->once())
            ->method('create')
            ->with(
                $this->equalTo($command->id),
                $this->equalTo($command->title),
                $this->equalTo($command->description),
                $this->equalTo($command->price),
                $this->equalTo($command->isActive),
                $this->equalTo($command->categories),
                $this->equalTo($command->images)
            )
            ->willReturn($product);

        $this->handleImageUpload
            ->expects($this->never())
            ->method('handle');

        $this->productRepository->expects($this->once())
            ->method('update')
            ->with($product)
            ->willReturn(1);

        // Act
        $result = ($this->handler)($command);

        // Assert
        $this->assertEquals(1, $result);
    }
}
