<?php

declare(strict_types=1);

namespace App\Category\Domain\DTO;

class CategoryDTO
{
    public function __construct(
        public ?int $id,
        public ?string $title,
        public ?string $slug,
        public ?int $parentId,
        public bool $isActive,
        public int $sortOrder = 0,
    ) {
    }
}
