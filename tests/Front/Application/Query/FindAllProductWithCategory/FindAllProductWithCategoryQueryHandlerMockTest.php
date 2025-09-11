<?php

declare(strict_types=1);

namespace App\Tests\Front\Application\Query\FindAllProductWithCategory;

use App\Product\Application\UseCases\Query\FindAllProductWithCategory\FindAllProductWithCategoryQuery;
use App\Product\Application\UseCases\Query\FindAllProductWithCategory\FindAllProductWithCategoryQueryHandler;
use App\Product\Domain\DTO\ProductCategoryDTO;
use App\Product\Domain\Repository\ProductImageQueryRepositoryInterface;
use App\Product\Domain\Repository\ProductQueryRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FindAllProductWithCategoryQueryHandlerMockTest extends TestCase
{
    private ProductQueryRepositoryInterface&MockObject $productRepository;

    private ProductImageQueryRepositoryInterface&MockObject $productImageRepository;

    private FindAllProductWithCategoryQueryHandler $handler;

    protected function setUp(): void
    {
        $this->productRepository = $this->createMock(ProductQueryRepositoryInterface::class);
        $this->productImageRepository = $this->createMock(ProductImageQueryRepositoryInterface::class);

        $this->handler = new FindAllProductWithCategoryQueryHandler(
            $this->productRepository,
            $this->productImageRepository
        );
    }

    public function test_invoke_should_return_product_category_dt_o_when_products_exist(): void
    {
        // Arrange
        $query = new FindAllProductWithCategoryQuery();

        $products = [
            [
                'id' => 1,
                'productTitle' => 'Product 1',
                'description' => 'Description 1',
                'slug' => 'product-1',
                'price' => 100,
                'is_active' => 1,
                'categories' => ['Category A'],
                'categoryTitle' => 'Category A',
            ],
            [
                'id' => 2,
                'productTitle' => 'Product 2',
                'description' => 'Description 2',
                'slug' => 'product-2',
                'price' => 200,
                'is_active' => 1,
                'categories' => ['Category B'],
                'categoryTitle' => 'Category B',
            ],
            [
                'id' => 3,
                'productTitle' => 'Product 3',
                'description' => 'Description 3',
                'slug' => 'product-3',
                'price' => 150,
                'is_active' => 1,
                'categories' => ['Category A'],
                'categoryTitle' => 'Category A',
            ],
        ];

        $productIds = [1, 2, 3];

        $productImages = [
            1 => ['image1.jpg', 'image2.jpg'],
            2 => ['image3.jpg'],
            3 => ['image4.jpg', 'image5.jpg'],
        ];

        $this->productRepository->expects($this->once())
            ->method('findAllProductCategory')
            ->willReturn($products);

        $this->productImageRepository->expects($this->once())
            ->method('findProductImageByProductIds')
            ->with($productIds)
            ->willReturn($productImages);

        // Act
        $result = ($this->handler)($query);

        // Assert
        $this->assertInstanceOf(ProductCategoryDTO::class, $result);

        // Check categories
        $categoryTitles = $result->getCategoryTitles();
        $this->assertCount(2, $categoryTitles);
        $this->assertContains('Category A', $categoryTitles);
        $this->assertContains('Category B', $categoryTitles);

        // Check products in Category A
        $categoryAProducts = $result->getProductsByCategory('Category A');
        $this->assertCount(2, $categoryAProducts);

        // Check products in Category B
        $categoryBProducts = $result->getProductsByCategory('Category B');
        $this->assertCount(1, $categoryBProducts);
    }

    public function test_invoke_should_return_null_when_no_products_found(): void
    {
        // Arrange
        $query = new FindAllProductWithCategoryQuery();

        $this->productRepository->expects($this->once())
            ->method('findAllProductCategory')
            ->willReturn([]);

        $this->productImageRepository->expects($this->never())
            ->method('findProductImageByProductIds');

        // Act
        $result = ($this->handler)($query);

        // Assert
        $this->assertNull($result);
    }

    public function test_invoke_should_handle_products_without_categories(): void
    {
        // Arrange
        $query = new FindAllProductWithCategoryQuery();

        $products = [
            [
                'id' => 1,
                'productTitle' => 'Product 1',
                'description' => 'Description 1',
                'slug' => 'product-1',
                'price' => 100,
                'is_active' => 1,
                // No categoryTitle or categories
            ],
        ];

        $productIds = [1];

        $productImages = [
            1 => ['image1.jpg'],
        ];

        $this->productRepository->expects($this->once())
            ->method('findAllProductCategory')
            ->willReturn($products);

        $this->productImageRepository->expects($this->once())
            ->method('findProductImageByProductIds')
            ->with($productIds)
            ->willReturn($productImages);

        // Act
        $result = ($this->handler)($query);

        // Assert
        $this->assertInstanceOf(ProductCategoryDTO::class, $result);

        // Products without category should be placed in 'Uncategorized'
        $categoryTitles = $result->getCategoryTitles();
        $this->assertCount(1, $categoryTitles);
        $this->assertContains('Uncategorized', $categoryTitles);

        $uncategorizedProducts = $result->getProductsByCategory('Uncategorized');
        $this->assertCount(1, $uncategorizedProducts);
    }
}
