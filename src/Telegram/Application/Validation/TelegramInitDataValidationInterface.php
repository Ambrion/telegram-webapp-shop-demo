<?php

declare(strict_types=1);

namespace App\Telegram\Application\Validation;

interface TelegramInitDataValidationInterface
{
    /**
     * Validate telegram incoming data.
     *
     * @param array<string, mixed> $params
     */
    public function validateTelegramIncomingData(array $params): void;

    /**
     * Validate telegram user.
     *
     * @param array<string, mixed> $params
     */
    public function validateTelegramUser(array $params): void;
}
