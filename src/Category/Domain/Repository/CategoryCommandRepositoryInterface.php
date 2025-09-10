<?php

declare(strict_types=1);

namespace App\Category\Domain\Repository;

use App\Category\Domain\Entity\Category;
use Doctrine\DBAL\Exception;

interface CategoryCommandRepositoryInterface
{
    /**
     * Add category.
     *
     * @throws Exception
     */
    public function add(Category $category): int;

    /**
     * Update category.
     *
     * @throws Exception
     */
    public function update(Category $category): int;
}
