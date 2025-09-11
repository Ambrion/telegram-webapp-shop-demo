<?php

declare(strict_types=1);

namespace App\Product\Application\UseCases\Query\FindProductByTitle;

use App\Product\Domain\DTO\ProductDTO;
use App\Product\Domain\Repository\ProductCategoryQueryRepositoryInterface;
use App\Product\Domain\Repository\ProductImageQueryRepositoryInterface;
use App\Product\Domain\Repository\ProductQueryRepositoryInterface;
use App\Shared\Application\Query\QueryHandlerInterface;

readonly class FindProductByTitleQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private ProductQueryRepositoryInterface $productRepository,
        private ProductCategoryQueryRepositoryInterface $productCategoryRepository,
        private ProductImageQueryRepositoryInterface $productImageRepository,
    ) {
    }

    public function __invoke(FindProductByTitleQuery $query): ?ProductDTO
    {
        $result = $this->productRepository->findOneByTitle($query->title);

        if (!$result) {
            return null;
        }

        $categories = $this->productCategoryRepository->listProductCategoryIds($result['id']);
        $images = $this->productImageRepository->findProductImage($result['id']);

        return new ProductDTO(
            $result['id'],
            $result['title'],
            $result['description'],
            $result['slug'],
            $result['price'],
            (bool) $result['is_active'],
            $categories,
            $images
        );
    }
}
