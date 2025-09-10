<?php

declare(strict_types=1);

namespace App\Front\Infrastructure\Adapter;

use Symfony\Component\HttpFoundation\JsonResponse;

interface PaymentAdapterInterface
{
    public function createPaymentRequest(int $orderId): JsonResponse;
}
