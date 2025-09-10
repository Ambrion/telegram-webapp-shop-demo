<?php

declare(strict_types=1);

namespace App\Order\Application\UseCases\Command\UpdateOrderAfterSuccessfulPayment;

use App\Order\Domain\Repository\OrderCommandRepositoryInterface;
use App\Order\Domain\Service\OrderStatus;
use App\Shared\Application\Command\CommandHandlerInterface;

readonly class UpdateOrderAfterSuccessfulPaymentCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private OrderCommandRepositoryInterface $repository,
    ) {
    }

    public function __invoke(UpdateOrderAfterSuccessfulPaymentCommand $orderCommand): int
    {
        return $this->repository->update(
            $orderCommand->userUlid,
            $orderCommand->invoice,
            $orderCommand->providerPaymentChargeId,
            OrderStatus::ORDER_STATUS_PAYMENT_SUCCESS
        );
    }
}
