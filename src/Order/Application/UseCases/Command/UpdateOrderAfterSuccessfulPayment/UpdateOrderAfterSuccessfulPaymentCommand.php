<?php

declare(strict_types=1);

namespace App\Order\Application\UseCases\Command\UpdateOrderAfterSuccessfulPayment;

use App\Shared\Application\Command\CommandInterface;

class UpdateOrderAfterSuccessfulPaymentCommand implements CommandInterface
{
    public function __construct(
        public string $userUlid,
        public string $invoice,
        public string $providerPaymentChargeId,
    ) {
    }
}
