<?php

declare(strict_types=1);

namespace App\Payment\Infrastructure\Adapter;

use App\Telegram\Infrastructure\Api\TelegramApiInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

readonly class TelegramAdapter implements TelegramAdapterInterface
{
    public function __construct(private TelegramApiInterface $api)
    {
    }

    /**
     * @param array<string, mixed> $postData
     */
    public function telegramApiRequest(array $postData, string $method): JsonResponse
    {
        return $this->api->telegramApiRequest($postData, $method);
    }
}
