<?php

declare(strict_types=1);

namespace App\Front\Infrastructure\Adapter;

use App\Telegram\Infrastructure\Api\TelegramApiInterface;

readonly class TelegramAdapter implements TelegramAdapterInterface
{
    public function __construct(private TelegramApiInterface $api)
    {
    }

    /**
     * Telegram webhook handle.
     *
     * @param array<string, mixed> $data
     */
    public function handle(array $data): void
    {
        $this->api->handle($data);
    }

    /**
     * Validate telegram incoming data.
     *
     * @param array<string, mixed> $params
     */
    public function validateTelegramIncomingData(array $params): void
    {
        $this->api->validateTelegramIncomingData($params);
    }
}
