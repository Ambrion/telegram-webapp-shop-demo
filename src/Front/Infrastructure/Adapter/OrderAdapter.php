<?php

declare(strict_types=1);

namespace App\Front\Infrastructure\Adapter;

use App\Order\Infrastructure\Api\Public\OrderApiInterface;

readonly class OrderAdapter implements OrderAdapterInterface
{
    public function __construct(private OrderApiInterface $api)
    {
    }

    /**
     * Create invoice.
     *
     * @param array<string, mixed> $cart
     * @param array<string, mixed> $cartUser
     */
    public function createInvoice(array $cart, array $cartUser): ?int
    {
        return $this->api->createInvoice($cart, $cartUser);
    }
}
