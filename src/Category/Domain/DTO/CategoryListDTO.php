<?php

declare(strict_types=1);

namespace App\Category\Domain\DTO;

readonly class CategoryListDTO
{
    public function __construct(
        public int $id,

        public string $title,

        public string $slug,

        public ?string $parentTitle,

        public ?bool $isActive,

        public string $categoryStatus,

        public ?int $sortOrder = 0,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            title: $data['title'],
            slug: $data['slug'],
            parentTitle: $data['parent_title'],
            isActive: (bool) $data['is_active'],
            categoryStatus: $data['category_status'],
            sortOrder: $data['sort_order']
        );
    }
}
