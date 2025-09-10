<?php

declare(strict_types=1);

namespace App\Front\Infrastructure\Adapter;

interface TelegramAdapterInterface
{
    /**
     * Telegram webhook handle.
     *
     * @param array<string, mixed> $data
     */
    public function handle(array $data): void;

    /**
     * Validate telegram incoming data.
     *
     * @param array<string, mixed> $params
     */
    public function validateTelegramIncomingData(array $params): void;
}
