<?php

declare(strict_types=1);

namespace App\Tests\Product\Application\Query\FindProductById;

use App\Product\Application\UseCases\Query\FindProductById\FindProductByIdQuery;
use App\Product\Application\UseCases\Query\FindProductById\FindProductByIdQueryHandler;
use App\Product\Domain\DTO\ProductDTO;
use App\Product\Domain\Repository\ProductCategoryQueryRepositoryInterface;
use App\Product\Domain\Repository\ProductImageQueryRepositoryInterface;
use App\Product\Domain\Repository\ProductQueryRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FindProductByIdQueryHandlerMockTest extends TestCase
{
    private ProductQueryRepositoryInterface&MockObject $productRepositoryMock;

    private ProductCategoryQueryRepositoryInterface&MockObject $productCategoryRepositoryMock;

    private ProductImageQueryRepositoryInterface&MockObject $productImageRepositoryMock;

    private FindProductByIdQueryHandler $handler;

    protected function setUp(): void
    {
        $this->productRepositoryMock = $this->createMock(ProductQueryRepositoryInterface::class);
        $this->productCategoryRepositoryMock = $this->createMock(ProductCategoryQueryRepositoryInterface::class);
        $this->productImageRepositoryMock = $this->createMock(ProductImageQueryRepositoryInterface::class);

        $this->handler = new FindProductByIdQueryHandler(
            $this->productRepositoryMock,
            $this->productCategoryRepositoryMock,
            $this->productImageRepositoryMock
        );
    }

    public function test_find_product_by_id_returns_product_dto(): void
    {
        // Arrange
        $productId = 1;
        $price = 999;
        $query = new FindProductByIdQuery($productId);

        $productData = [
            'id' => $productId,
            'title' => 'Test Product',
            'description' => 'Test Description',
            'slug' => 'test-product',
            'price' => $price,
            'is_active' => 1,
        ];

        $categories = [1, 2, 3];
        $images = [
            ['id' => 1, 'path' => 'image1.jpg'],
            ['id' => 2, 'path' => 'image2.jpg'],
        ];

        $this->productRepositoryMock
            ->expects($this->once())
            ->method('findById')
            ->with($productId)
            ->willReturn($productData);

        $this->productCategoryRepositoryMock
            ->expects($this->once())
            ->method('listProductCategoryIds')
            ->with($productId)
            ->willReturn($categories);

        $this->productImageRepositoryMock
            ->expects($this->once())
            ->method('findProductImage')
            ->with($productId)
            ->willReturn($images);

        // Act
        $result = ($this->handler)($query);

        // Assert
        $this->assertInstanceOf(ProductDTO::class, $result);
        $this->assertEquals($productId, $result->id);
        $this->assertEquals('Test Product', $result->title);
        $this->assertEquals('Test Description', $result->description);
        $this->assertEquals('test-product', $result->slug);
        $this->assertEquals($price, $result->price);
        $this->assertTrue($result->isActive);
        $this->assertEquals($categories, $result->categories);
        $this->assertEquals($images, $result->images);
    }

    public function test_find_product_by_id_returns_null_when_product_not_found(): void
    {
        // Arrange
        $productId = 999;
        $query = new FindProductByIdQuery($productId);
        $array = [];

        $this->productRepositoryMock
            ->expects($this->once())
            ->method('findById')
            ->with($productId)
            ->willReturn($array);

        // Act
        $result = ($this->handler)($query);

        // Assert
        $this->assertNull($result);
    }
}
