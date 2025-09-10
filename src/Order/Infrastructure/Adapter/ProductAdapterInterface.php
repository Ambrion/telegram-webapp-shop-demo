<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Adapter;

use App\Shared\Domain\DTO\OrderProductDTO;

interface ProductAdapterInterface
{
    /**
     * Create products from cart.
     *
     * @param array<string, mixed> $cart
     *
     * @return OrderProductDTO[]|null
     */
    public function createProductFromCart(array $cart): ?array;
}
