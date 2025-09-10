<?php

declare(strict_types=1);

namespace App\Payment\Infrastructure\Api;

use Symfony\Component\HttpFoundation\JsonResponse;

interface PaymentApiInterface
{
    public function createPaymentRequest(int $orderId): JsonResponse;
}
