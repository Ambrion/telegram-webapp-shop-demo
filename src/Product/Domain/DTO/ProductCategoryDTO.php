<?php

declare(strict_types=1);

namespace App\Product\Domain\DTO;

readonly class ProductCategoryDTO
{
    /**
     * @param array<string, array<ProductDTO>> $categories
     */
    public function __construct(
        public array $categories,
    ) {
    }

    /**
     * Get products by category title.
     *
     * @return array<ProductDTO>
     */
    public function getProductsByCategory(string $categoryTitle): array
    {
        return $this->categories[$categoryTitle] ?? [];
    }

    /**
     * Get all category titles.
     *
     * @return string[]
     */
    public function getCategoryTitles(): array
    {
        return array_keys($this->categories);
    }
}
