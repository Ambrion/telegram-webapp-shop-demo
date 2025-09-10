<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Api\Public;

use App\Order\Domain\DTO\OrderDTO;

interface OrderApiInterface
{
    /**
     * Find order by id.
     */
    public function findOrderByIdQuery(int $id): ?OrderDTO;

    /**
     * Update order after successful payment.
     */
    public function updateOrderAfterSuccessfulPayment(string $ulid, string $invoice, string $providerPaymentChargeId): int;

    /**
     * @param array<string, mixed> $cart
     * @param array<string, mixed> $cartUser
     */
    public function createInvoice(array $cart, array $cartUser): ?int;
}
