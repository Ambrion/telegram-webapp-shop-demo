<?php

declare(strict_types=1);

namespace App\Tests\Product\Application\UseCases\Command\ProductCategory;

use App\Product\Application\UseCases\Command\CreateProduct\CreateProductCommand;
use App\Product\Application\UseCases\Command\CreateProduct\CreateProductCommandHandler;
use App\Product\Domain\Entity\Product;
use App\Product\Domain\Factory\ProductFactory;
use App\Product\Domain\Repository\ProductCommandRepositoryInterface;
use App\Product\Domain\Service\HandleProductImageUploadInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CreateProductCommandHandlerMockTest extends TestCase
{
    private ProductCommandRepositoryInterface&MockObject $repository;
    private ProductFactory&MockObject $productFactory;
    private CreateProductCommandHandler $handler;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(ProductCommandRepositoryInterface::class);
        $this->productFactory = $this->createMock(ProductFactory::class);
        $this->handleImageUpload = $this->createMock(HandleProductImageUploadInterface::class);

        $this->handler = new CreateProductCommandHandler(
            $this->repository,
            $this->productFactory,
            $this->handleImageUpload
        );
    }

    public function test_invoke_should_create_product_and_return_id(): void
    {
        // Arrange
        $command = new CreateProductCommand(
            title: 'Test Product',
            description: 'Test Description',
            price: 100,
            isActive: true,
            categories: [1, 2],
            images: []
        );

        $product = $this->createMock(Product::class);
        $productId = 1;

        $this->productFactory
            ->expects($this->once())
            ->method('create')
            ->with(
                $this->isNull(),
                $this->equalTo($command->title),
                $this->equalTo($command->description),
                $this->equalTo($command->price),
                $this->equalTo($command->isActive),
                $this->equalTo($command->categories)
            )
            ->willReturn($product);

        $this->handleImageUpload
            ->expects($this->never())
            ->method('handle');

        $this->repository
            ->expects($this->once())
            ->method('add')
            ->with($product)
            ->willReturn($productId);

        // Act
        $result = ($this->handler)($command);

        // Assert
        $this->assertEquals($productId, $result);
    }
}
