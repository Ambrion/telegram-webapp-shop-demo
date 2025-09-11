<?php

declare(strict_types=1);

namespace App\Product\Domain\DTO;

readonly class ProductForOrderDTO
{
    public function __construct(
        public int $id,

        public string $title,

        public int $price,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): ProductForOrderDTO
    {
        return new ProductForOrderDTO(
            $data['id'],
            $data['title'],
            $data['price']
        );
    }
}
