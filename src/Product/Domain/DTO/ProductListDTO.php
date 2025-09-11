<?php

declare(strict_types=1);

namespace App\Product\Domain\DTO;

readonly class ProductListDTO
{
    public function __construct(
        public int $id,

        public string $title,

        public string $slug,

        public ?string $description,

        public int $price,

        public ?bool $isActive,

        public string $productStatus,

        public ?string $filePath,
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
            description: $data['description'] ?? null,
            price: $data['price'],
            isActive: (bool) $data['is_active'],
            productStatus: $data['product_status'],
            filePath: $data['file_path'] ?? null
        );
    }
}
