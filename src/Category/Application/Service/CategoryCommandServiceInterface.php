<?php

declare(strict_types=1);

namespace App\Category\Application\Service;

use App\Category\Application\DTO\CategoryDTO;

interface CategoryCommandServiceInterface
{
    /**
     * Create category.
     *
     * @return int id category
     */
    public function createCategory(string $title, ?int $parentId, bool $isActive, ?int $sortOrder = 0): int;

    /**
     * Update category.
     */
    public function updateCategory(int $id, string $title, ?int $parentId, bool $isActive, ?int $sortOrder): int;

    /**
     * Create empty category DTO.
     */
    public function createEmptyCategoryDTO(): CategoryDTO;
}
