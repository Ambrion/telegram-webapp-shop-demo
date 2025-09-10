<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\Adapter\Category;

use App\Category\Application\DTO\CategoryDTO;
use App\Category\Application\DTO\CategoryListDTO;
use App\Category\Domain\Filter\CategoryFilterInterface;
use App\Category\Infrastructure\Api\Admin\CategoryApiInterface;

readonly class CategoryAdapter implements CategoryAdapterInterface
{
    public function __construct(private CategoryApiInterface $api)
    {
    }

    /**
     * Find category by id.
     */
    public function findCategoryByIdQuery(int $id): ?CategoryDTO
    {
        return $this->api->findCategoryByIdQuery($id);
    }

    /**
     * Find category by title.
     */
    public function findCategoryByTitleQuery(string $title): ?CategoryDTO
    {
        return $this->api->findCategoryByTitleQuery($title);
    }

    /**
     * Find all category by criteria.
     *
     * @return array<CategoryDTO>|null
     */
    public function findAllByCriteriaQuery(CategoryFilterInterface $filter): ?array
    {
        return $this->api->findAllByCriteriaQuery($filter);
    }

    /**
     * Update category.
     */
    public function updateCategory(CategoryDTO $categoryDTO): ?int
    {
        return $this->api->updateCategory($categoryDTO);
    }

    /**
     * Create empty category DTO.
     */
    public function createEmptyCategoryDTO(): CategoryDTO
    {
        return $this->api->createEmptyCategoryDTO();
    }

    /**
     * Create category.
     */
    public function createCategory(CategoryDTO $categoryDTO): int
    {
        return $this->api->createCategory($categoryDTO);
    }

    /**
     * Count all category by filter.
     */
    public function countAllCategoryByFilter(CategoryFilterInterface $filter): int
    {
        return $this->api->countAllCategoryByFilter($filter);
    }

    /**
     * List categories with pagination.
     *
     * @return array<CategoryListDTO>|null
     */
    public function listCategoryWithPagination(CategoryFilterInterface $filter, int $offset, int $limit): ?array
    {
        return $this->api->listCategoryWithPagination($filter, $offset, $limit);
    }
}
