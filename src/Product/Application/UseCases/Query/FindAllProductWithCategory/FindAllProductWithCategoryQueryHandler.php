<?php

declare(strict_types=1);

namespace App\Product\Application\UseCases\Query\FindAllProductWithCategory;

use App\Product\Domain\DTO\ProductCategoryDTO;
use App\Product\Domain\DTO\ProductDTO;
use App\Product\Domain\Repository\ProductImageQueryRepositoryInterface;
use App\Product\Domain\Repository\ProductQueryRepositoryInterface;
use App\Shared\Application\Query\QueryHandlerInterface;

readonly class FindAllProductWithCategoryQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private ProductQueryRepositoryInterface $productRepository,
        private ProductImageQueryRepositoryInterface $productImageRepository,
    ) {
    }

    public function __invoke(FindAllProductWithCategoryQuery $query): ?ProductCategoryDTO
    {
        $products = $this->productRepository->findAllProductCategory();

        if (!$products) {
            return null;
        }

        $productIds = array_unique(array_column($products, 'id'));

        $productImages = $this->productImageRepository->findProductImageByProductIds($productIds);

        $result = [];
        foreach ($products as $product) {
            $productDTO = new ProductDTO(
                id: $product['id'],
                title: $product['productTitle'],
                description: $product['description'],
                slug: $product['slug'],
                price: $product['price'],
                isActive: !isset($product['is_active']) || $product['is_active'],
                categories: $product['categories'] ?? [],
                images: $productImages[$product['id']] ?? []
            );

            $categoryTitle = $product['categoryTitle'] ?? 'Uncategorized';
            $result[$categoryTitle][] = $productDTO;
        }

        return new ProductCategoryDTO($result);
    }
}
