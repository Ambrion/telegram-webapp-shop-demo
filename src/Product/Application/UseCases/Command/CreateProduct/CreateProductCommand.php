<?php

declare(strict_types=1);

namespace App\Product\Application\UseCases\Command\CreateProduct;

use App\Shared\Application\Command\CommandInterface;

class CreateProductCommand implements CommandInterface
{
    /**
     * @param array<int>    $categories
     * @param array<string> $images
     */
    public function __construct(
        public string $title,
        public ?string $description,
        public int $price,
        public bool $isActive,
        public array $categories,
        public array $images,
    ) {
    }
}
