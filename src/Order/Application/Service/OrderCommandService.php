<?php

declare(strict_types=1);

namespace App\Order\Application\Service;

use App\Order\Application\UseCases\Command\CreateOrder\CreateOrderCommand;
use App\Order\Application\UseCases\Command\UpdateOrderAfterSuccessfulPayment\UpdateOrderAfterSuccessfulPaymentCommand;
use App\Order\Domain\Service\OrderCommandServiceInterface;
use App\Shared\Application\Command\CommandBusInterface;

readonly class OrderCommandService implements OrderCommandServiceInterface
{
    public function __construct(
        private CommandBusInterface $commandBus,
    ) {
    }

    /**
     * Update order after successful payment.
     */
    public function updateOrderAfterSuccessfulPayment(string $ulid, string $invoice, string $providerPaymentChargeId): int
    {
        $command = new UpdateOrderAfterSuccessfulPaymentCommand(
            userUlid: $ulid,
            invoice: $invoice,
            providerPaymentChargeId: $providerPaymentChargeId
        );

        return $this->commandBus->execute($command);
    }

    /**
     * @param array<string, mixed> $products
     */
    public function createOrder(string $ulid, string $currency, int $totalAmount, string $paymentMethod, int $orderStatusId, array $products): int
    {
        $command = new CreateOrderCommand($ulid, $currency, $totalAmount, $paymentMethod, $orderStatusId, $products);

        return $this->commandBus->execute($command);
    }
}
