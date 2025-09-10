<?php

declare(strict_types=1);

namespace App\Telegram\Application\Validation;

interface TelegramPreCheckoutQueryValidationInterface
{
    /**
     * Validate telegram pre checkout query.
     *
     * @param array<string, mixed> $data
     */
    public function validate(array $data): bool;
}
