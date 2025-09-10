<?php

declare(strict_types=1);

namespace App\Telegram\Infrastructure\Validation;

use App\Telegram\Application\Validation\TelegramPreCheckoutQueryValidationInterface;

readonly class TelegramPreCheckoutQueryValidation implements TelegramPreCheckoutQueryValidationInterface
{
    /**
     * Validate telegram pre checkout query.
     *
     * @param array<string, mixed> $data
     */
    public function validate(array $data): bool
    {
        $result = true;

        if (empty($data['from']) || empty($data['total_amount'])) {
            $result = false;
        }

        return $result;
    }
}
