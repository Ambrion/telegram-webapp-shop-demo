<?php

declare(strict_types=1);

namespace App\Product\Application\Command\DeleteProductImage;

use App\Shared\Application\Command\CommandInterface;

class DeleteProductImageCommand implements CommandInterface
{
    /**
     * @param array<int> $imageIds
     */
    public function __construct(public int $productId, public array $imageIds)
    {
    }
}
