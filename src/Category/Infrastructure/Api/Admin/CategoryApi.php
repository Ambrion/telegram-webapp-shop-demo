<?php

declare(strict_types=1);

namespace App\Category\Infrastructure\Api\Admin;

use App\Category\Application\DTO\CategoryDTO;
use App\Category\Application\DTO\CategoryListDTO;
use App\Category\Application\Service\CategoryCommandServiceInterface;
use App\Category\Application\Service\CategoryQueryServiceInterface;
use App\Category\Domain\Filter\CategoryFilterInterface;

readonly class CategoryApi implements CategoryApiInterface
{
    public function __construct(
        private CategoryCommandServiceInterface $categoryCommandService,
        private CategoryQueryServiceInterface $categoryQueryService,
    ) {
    }

    /**
     * Find category by id.
     */
    public function findCategoryByIdQuery(int $id): ?CategoryDTO
    {
        return $this->categoryQueryService->findCategoryByIdQuery($id);
    }

    /**
     * Find category by title.
     */
    public function findCategoryByTitleQuery(string $title): ?CategoryDTO
    {
        return $this->categoryQueryService->findCategoryByTitleQuery($title);
    }

    /**
     * Find all category by criteria.
     *
     * @return array<CategoryDTO>|null
     */
    public function findAllByCriteriaQuery(CategoryFilterInterface $filter): ?array
    {
        return $this->categoryQueryService->findAllByCriteriaQuery($filter);
    }

    /**
     * Update category.
     */
    public function updateCategory(CategoryDTO $categoryDTO): ?int
    {
        return $this->categoryCommandService
            ->updateCategory(
                $categoryDTO->id,
                $categoryDTO->title,
                $categoryDTO->parentId,
                $categoryDTO->isActive,
                $categoryDTO->sortOrder
            );
    }

    /**
     * Create empty category DTO.
     */
    public function createEmptyCategoryDTO(): CategoryDTO
    {
        return $this->categoryCommandService->createEmptyCategoryDTO();
    }

    /**
     * Create category.
     *
     * @return int category id
     */
    public function createCategory(CategoryDTO $categoryDTO): int
    {
        return $this->categoryCommandService
            ->createCategory(
                $categoryDTO->title,
                $categoryDTO->parentId,
                $categoryDTO->isActive,
                $categoryDTO->sortOrder
            );
    }

    /**
     * Count all category by filter.
     */
    public function countAllCategoryByFilter(CategoryFilterInterface $filter): int
    {
        return $this->categoryQueryService->countAllCategoryByFilter($filter);
    }

    /**
     * List categories with pagination.
     *
     * @return array<CategoryListDTO>|null
     */
    public function listCategoryWithPagination(CategoryFilterInterface $filter, int $offset, int $limit): ?array
    {
        return $this->categoryQueryService->listCategoryWithPagination($filter, $offset, $limit);
    }
}
