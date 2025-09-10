<?php

declare(strict_types=1);

namespace App\Product\Application\Command\UpdateProduct;

use App\Shared\Application\Command\CommandInterface;

class UpdateProductCommand implements CommandInterface
{
    /**
     * @param array<int>|null           $categories
     * @param array<string, mixed>|null $images
     */
    public function __construct(
        public int $id,
        public string $title,
        public ?string $description,
        public int $price,
        public bool $isActive,
        public ?array $categories,
        public ?array $images,
    ) {
    }
}
