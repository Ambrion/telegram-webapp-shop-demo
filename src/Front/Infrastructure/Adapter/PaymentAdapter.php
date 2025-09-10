<?php

declare(strict_types=1);

namespace App\Front\Infrastructure\Adapter;

use App\Payment\Infrastructure\Api\PaymentApiInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

readonly class PaymentAdapter implements PaymentAdapterInterface
{
    public function __construct(private PaymentApiInterface $api)
    {
    }

    public function createPaymentRequest(int $orderId): JsonResponse
    {
        return $this->api->createPaymentRequest($orderId);
    }
}
