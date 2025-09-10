<?php

declare(strict_types=1);

namespace App\Order\Domain\Model;

readonly class OrderProduct
{
    public function __construct(
        public int $productId,
        public string $title,
        public int $quantity,
        public int $price,
        public int $totalPrice,
    ) {
    }
}
