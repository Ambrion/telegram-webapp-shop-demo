<?php

declare(strict_types=1);

namespace App\Product\Application\Command\ReorderProductImage;

use App\Shared\Application\Command\CommandInterface;

class ReorderProductImageCommand implements CommandInterface
{
    /**
     * @param array<int> $imageIds
     */
    public function __construct(public int $productId, public array $imageIds)
    {
    }
}
