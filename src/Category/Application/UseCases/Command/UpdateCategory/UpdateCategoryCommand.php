<?php

declare(strict_types=1);

namespace App\Category\Application\UseCases\Command\UpdateCategory;

use App\Shared\Application\Command\CommandInterface;

class UpdateCategoryCommand implements CommandInterface
{
    public function __construct(
        public int $id,
        public string $title,
        public ?int $parentId,
        public bool $isActive,
        public ?int $sortOrder,
    ) {
    }
}
