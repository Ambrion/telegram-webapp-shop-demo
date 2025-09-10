<?php

declare(strict_types=1);

namespace App\Order\Domain\Service;

interface OrderCommandServiceInterface
{
    /**
     * Update order after successful payment.
     */
    public function updateOrderAfterSuccessfulPayment(string $ulid, string $invoice, string $providerPaymentChargeId): int;

    /**
     * @param array<string, mixed> $products
     */
    public function createOrder(string $ulid, string $currency, int $totalAmount, string $paymentMethod, int $orderStatusId, array $products): int;
}
