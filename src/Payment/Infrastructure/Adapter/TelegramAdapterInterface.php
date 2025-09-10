<?php

declare(strict_types=1);

namespace App\Payment\Infrastructure\Adapter;

use Symfony\Component\HttpFoundation\JsonResponse;

interface TelegramAdapterInterface
{
    /**
     * Telegram API request.
     *
     * @param array<string, mixed> $postData
     */
    public function telegramApiRequest(array $postData, string $method): JsonResponse;
}
