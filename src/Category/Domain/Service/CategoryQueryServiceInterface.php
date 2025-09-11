<?php

declare(strict_types=1);

namespace App\Category\Domain\Service;

use App\Category\Domain\DTO\CategoryDTO;
use App\Category\Domain\DTO\CategoryListDTO;
use App\Category\Domain\Filter\CategoryFilterInterface;

interface CategoryQueryServiceInterface
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
