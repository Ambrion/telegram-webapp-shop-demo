<?php

declare(strict_types=1);

namespace App\Product\Domain\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ProductForOrderDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Type('int')]
        public int $id,

        #[Assert\NotBlank]
        #[Assert\Type('string')]
        public string $title,

        #[Assert\NotBlank]
        #[Assert\Type('int')]
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
