<?php

declare(strict_types=1);

namespace App\Telegram\Application\Handler;

use Symfony\Component\HttpFoundation\JsonResponse;

interface TelegramWebhookHandlerInterface
{
    /**
     * @param array<string, mixed> $data
     */
    public function handle(array $data): void;

    /**
     * @param array<string, mixed> $postData
     */
    public function telegramApiRequest(array $postData, string $method): JsonResponse;
}
