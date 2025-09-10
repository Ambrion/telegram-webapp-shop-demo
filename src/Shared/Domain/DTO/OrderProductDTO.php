<?php

declare(strict_types=1);

namespace App\Shared\Domain\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class OrderProductDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Type('int')]
        public int $productId,

        #[Assert\NotBlank]
        #[Assert\Type('string')]
        public string $title,

        #[Assert\NotBlank]
        #[Assert\Type('int')]
        public int $quantity,

        #[Assert\NotBlank]
        #[Assert\Type('int')]
        public int $price,

        #[Assert\NotBlank]
        #[Assert\Type('int')]
        public int $totalPrice,

        #[Assert\NotBlank]
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
            $data['product_id'],
            $data['title'],
            $data['quantity'],
            $data['price'],
            $data['total_price'],
            $data['file_path'] ?? null
        );
    }
}
