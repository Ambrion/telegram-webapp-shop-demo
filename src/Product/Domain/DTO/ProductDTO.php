<?php

declare(strict_types=1);

namespace App\Product\Domain\DTO;

class ProductDTO
{
    public function __construct(
        public ?int $id,

        public ?string $title,

        public ?string $description,

        public ?string $slug,

        public ?int $price,

        public bool $isActive,

        /**
         * @var array<string|mixed>
         */
        public array $categories = [],

        /**
         * @var array<string|mixed>
         */
        public array $images = [],
    ) {
    }
}
