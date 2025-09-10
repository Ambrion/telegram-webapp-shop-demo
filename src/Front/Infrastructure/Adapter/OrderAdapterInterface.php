<?php

declare(strict_types=1);

namespace App\Front\Infrastructure\Adapter;

interface OrderAdapterInterface
{
    /**
     * Create invoice.
     *
     * @param array<string, mixed> $cart
     * @param array<string, mixed> $cartUser
     */
    public function createInvoice(array $cart, array $cartUser): ?int;
}
