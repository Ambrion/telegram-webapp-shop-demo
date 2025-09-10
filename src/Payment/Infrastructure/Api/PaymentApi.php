<?php

declare(strict_types=1);

namespace App\Payment\Infrastructure\Api;

use App\Payment\Domain\Service\CreatePaymentServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

readonly class PaymentApi implements PaymentApiInterface
{
    public function __construct(private CreatePaymentServiceInterface $createPayment)
    {
    }

    public function createPaymentRequest(int $orderId): JsonResponse
    {
        return $this->createPayment->createPaymentRequest($orderId);
    }
}
