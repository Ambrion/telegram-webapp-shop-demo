<?php

declare(strict_types=1);

namespace App\Product\Domain\Service;

interface HandleProductImageUploadInterface
{
    /**
     * @param array<string, mixed> $images
     */
    public function handle(int $productId, array $images): void;
}
