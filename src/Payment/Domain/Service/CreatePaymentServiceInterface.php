<?php

declare(strict_types=1);

namespace App\Payment\Domain\Service;

use Symfony\Component\HttpFoundation\JsonResponse;

interface CreatePaymentServiceInterface
{
    public function createPaymentRequest(int $orderId): JsonResponse;
}
