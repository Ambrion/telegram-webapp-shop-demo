<?php

declare(strict_types=1);

namespace App\Order\Domain\Repository;

use App\Order\Domain\Entity\Order;

interface OrderCommandRepositoryInterface
{
    public function add(Order $order): int;

    public function update(string $userUlid, string $invoice, string $providerPaymentChargeId, int $orderStatusId): int;
}
