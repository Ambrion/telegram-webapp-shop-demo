<?php

declare(strict_types=1);

namespace App\Product\Application\UseCases\Command\CreateProductImage;

use App\Shared\Application\Command\CommandInterface;

class CreateProductImageCommand implements CommandInterface
{
    /**
     * @param array<string> $images
     */
    public function __construct(public int $productId, public array $images)
    {
    }
}
