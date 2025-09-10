<?php

declare(strict_types=1);

namespace App\Telegram\Infrastructure\Adapter;

interface OrderAdapterInterface
{
    /**
     * Update order after successful payment.
     */
    public function updateOrderAfterSuccessfulPayment(string $ulid, string $invoice, string $providerPaymentChargeId): int;
}
