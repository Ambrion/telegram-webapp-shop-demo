<?php

declare(strict_types=1);

namespace App\Telegram\Infrastructure\Api;

use App\Telegram\Application\Handler\TelegramWebhookHandlerInterface;
use App\Telegram\Application\Validation\TelegramInitDataValidationInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

readonly class TelegramApi implements TelegramApiInterface
{
    public function __construct(
        private TelegramInitDataValidationInterface $telegramInitDataValidation,
        private TelegramWebhookHandlerInterface $handler,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public function handle(array $data): void
    {
        $this->handler->handle($data);
    }

    /**
     * @param array<string, mixed> $postData
     */
    public function telegramApiRequest(array $postData, string $method): JsonResponse
    {
        return $this->handler->telegramApiRequest($postData, $method);
    }

    /**
     * Validate telegram incoming data.
     *
     * @param array<string, mixed> $params
     */
    public function validateTelegramIncomingData(array $params): void
    {
        $this->telegramInitDataValidation->validateTelegramIncomingData($params);
    }
}
