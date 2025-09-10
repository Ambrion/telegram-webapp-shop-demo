<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\Adapter\Category;

use App\Category\Application\DTO\CategoryDTO;
use App\Category\Application\DTO\CategoryListDTO;
use App\Category\Domain\Filter\CategoryFilterInterface;

interface CategoryAdapterInterface
{
    /**
     * Find category by id.
     */
    public function findCategoryByIdQuery(int $id): ?CategoryDTO;

    /**
     * Find category by title.
     */
    public function findCategoryByTitleQuery(string $title): ?CategoryDTO;

    /**
     * Find all category by criteria.
     *
     * @return array<CategoryDTO>|null
     */
    public function findAllByCriteriaQuery(CategoryFilterInterface $filter): ?array;

    /**
     * Update category.
     */
    public function updateCategory(CategoryDTO $categoryDTO): ?int;

    /**
     * Create empty category DTO.
     */
    public function createEmptyCategoryDTO(): CategoryDTO;

    /**
     * Create category.
     */
    public function createCategory(CategoryDTO $categoryDTO): int;

    /**
     * Count all category by filter.
     */
    public function countAllCategoryByFilter(CategoryFilterInterface $filter): int;

    /**
     * List categories with pagination.
     *
     * @return array<CategoryListDTO>|null
     */
    public function listCategoryWithPagination(CategoryFilterInterface $filter, int $offset, int $limit): ?array;
}
