<?php

declare(strict_types=1);

namespace App\Telegram\Infrastructure\Api;

use Symfony\Component\HttpFoundation\JsonResponse;

interface TelegramApiInterface
{
    /**
     * Telegram webhook handle.
     *
     * @param array<string, mixed> $data
     */
    public function handle(array $data): void;

    /**
     * Validate incoming telegram data.
     *
     * @param array<string, mixed> $params
     */
    public function validateTelegramIncomingData(array $params): void;

    /**
     * Telegram API request.
     *
     * @param array<string, mixed> $postData
     */
    public function telegramApiRequest(array $postData, string $method): JsonResponse;
}
