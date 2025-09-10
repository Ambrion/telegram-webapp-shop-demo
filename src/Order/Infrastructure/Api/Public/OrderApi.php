<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Api\Public;

use App\Order\Domain\DTO\OrderDTO;
use App\Order\Domain\Service\CreateInvoiceServiceInterface;
use App\Order\Domain\Service\OrderCommandServiceInterface;
use App\Order\Domain\Service\OrderQueryServiceInterface;

readonly class OrderApi implements OrderApiInterface
{
    public function __construct(
        private OrderCommandServiceInterface $orderCommandService,
        private OrderQueryServiceInterface $orderQueryService,
        private CreateInvoiceServiceInterface $createInvoiceService,
    ) {
    }

    /**
     * Find order by id.
     */
    public function findOrderByIdQuery(int $id): ?OrderDTO
    {
        return $this->orderQueryService->findOrderByIdQuery($id);
    }

    /**
     * Update order after successful payment.
     */
    public function updateOrderAfterSuccessfulPayment(string $ulid, string $invoice, string $providerPaymentChargeId): int
    {
        return $this->orderCommandService->updateOrderAfterSuccessfulPayment($ulid, $invoice, $providerPaymentChargeId);
    }

    /**
     * @param array<string, mixed> $cart
     * @param array<string, mixed> $cartUser
     */
    public function createInvoice(array $cart, array $cartUser): int
    {
        return $this->createInvoiceService->createInvoice($cart, $cartUser);
    }
}
