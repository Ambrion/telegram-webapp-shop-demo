<?php

declare(strict_types=1);

namespace App\Category\Application\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CategoryListDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Type('int')]
        public int $id,

        #[Assert\NotBlank]
        #[Assert\Type('string')]
        public string $title,

        #[Assert\NotBlank]
        #[Assert\Type('string')]
        public string $slug,

        #[Assert\Type('string')]
        public ?string $parentTitle,

        #[Assert\Type('bool')]
        public ?bool $isActive,

        #[Assert\NotBlank]
        #[Assert\Type('string')]
        public string $categoryStatus,

        #[Assert\Type('int')]
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
