<?php

declare(strict_types=1);

namespace App\Shared\Domain\DTO;


class OrderProductDTO
{
    public function __construct(
        public int $productId,

        public string $title,

        public int $quantity,

        public int $price,

        public int $totalPrice,

        public ?string $filePath,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['product_id'],
            $data['title'],
            $data['quantity'],
            $data['price'],
            $data['total_price'],
            $data['file_path'] ?? null
        );
    }
}
