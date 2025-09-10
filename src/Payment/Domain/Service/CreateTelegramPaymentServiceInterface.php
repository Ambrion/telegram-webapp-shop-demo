<?php

declare(strict_types=1);

namespace App\Payment\Domain\Service;

use Symfony\Component\HttpFoundation\JsonResponse;

interface CreateTelegramPaymentServiceInterface
{
    public function sendTelegramPaymentRequest(int $telegramId, string $invoice, int $totalAmount, string $currencyCode, string $paymentMethod): JsonResponse;
}
