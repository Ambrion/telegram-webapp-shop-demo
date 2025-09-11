<?php

declare(strict_types=1);

namespace App\Tests\Product\Application\Query\FindProductByTitle;

use App\Product\Application\UseCases\Query\FindProductByTitle\FindProductByTitleQuery;
use App\Product\Application\UseCases\Query\FindProductByTitle\FindProductByTitleQueryHandler;
use App\Product\Domain\DTO\ProductDTO;
use App\Product\Domain\Repository\ProductCategoryQueryRepositoryInterface;
use App\Product\Domain\Repository\ProductImageQueryRepositoryInterface;
use App\Product\Domain\Repository\ProductQueryRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FindProductByTitleQueryHandlerMockTest extends TestCase
{
    private ProductQueryRepositoryInterface&MockObject $productRepositoryMock;

    private ProductCategoryQueryRepositoryInterface&MockObject $productCategoryRepositoryMock;

    private ProductImageQueryRepositoryInterface&MockObject $productImageRepositoryMock;

    private FindProductByTitleQueryHandler $handler;

    protected function setUp(): void
    {
        $this->productRepositoryMock = $this->createMock(ProductQueryRepositoryInterface::class);
        $this->productCategoryRepositoryMock = $this->createMock(ProductCategoryQueryRepositoryInterface::class);
        $this->productImageRepositoryMock = $this->createMock(ProductImageQueryRepositoryInterface::class);

        $this->handler = new FindProductByTitleQueryHandler(
            $this->productRepositoryMock,
            $this->productCategoryRepositoryMock,
            $this->productImageRepositoryMock
        );
    }

    public function test_rind_product_by_id_returns_product_dto(): void
    {
        // Arrange
        $productId = 1;
        $title = 'Test Product';
        $price = 999;
        $query = new FindProductByTitleQuery($title);

        $productData = [
            'id' => $productId,
            'title' => $title,
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
            ->method('findOneByTitle')
            ->with($title)
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
        $this->assertEquals($title, $result->title);
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
        $title = 'Title not exists';
        $query = new FindProductByTitleQuery($title);

        $this->productRepositoryMock
            ->expects($this->once())
            ->method('findOneByTitle')
            ->with($title)
            ->willReturn([]);

        // Act
        $result = ($this->handler)($query);

        // Assert
        $this->assertNull($result);
    }
}
