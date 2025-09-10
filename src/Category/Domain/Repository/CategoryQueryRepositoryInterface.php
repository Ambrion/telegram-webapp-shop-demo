<?php

declare(strict_types=1);

namespace App\Category\Domain\Repository;

use App\Category\Domain\Filter\CategoryFilterInterface;

interface CategoryQueryRepositoryInterface
{
    /**
     * List categories with filter and pagination.
     *
     * @return array<array<string, mixed>>
     */
    public function listCategoryWithPagination(CategoryFilterInterface $categoryFilter, int $offset, int $limit): array;

    /**
     * Count all category.
     */
    public function countAll(CategoryFilterInterface $categoryFilter): int;

    /**
     * Find category by id.
     *
     * @return array<string, mixed>
     */
    public function findById(int $id): array;

    /**
     * Find one by title.
     *
     * @return array<string, mixed>
     */
    public function findOneByTitle(string $title): array;

    /**
     * Find all categories by some criteria.
     *
     * @return array<array<string, mixed>>
     */
    public function findAllByCriteria(CategoryFilterInterface $categoryFilter): array;
}
