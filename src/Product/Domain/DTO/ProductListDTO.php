<?php

declare(strict_types=1);

namespace App\Product\Domain\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ProductListDTO
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
        public ?string $description,

        #[Assert\NotBlank]
        #[Assert\Type('int')]
        public int $price,

        #[Assert\Type('bool')]
        public ?bool $isActive,

        #[Assert\NotBlank]
        #[Assert\Type('string')]
        public string $productStatus,

        #[Assert\Type('string')]
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
