<?php

declare(strict_types=1);

namespace App\Telegram\Infrastructure\Adapter;

use App\Order\Infrastructure\Api\Public\OrderApiInterface;

readonly class OrderAdapter implements OrderAdapterInterface
{
    public function __construct(private OrderApiInterface $api)
    {
    }

    /**
     * Update order after successful payment.
     */
    public function updateOrderAfterSuccessfulPayment(string $ulid, string $invoice, string $providerPaymentChargeId): int
    {
        return $this->api->updateOrderAfterSuccessfulPayment($ulid, $invoice, $providerPaymentChargeId);
    }
}
