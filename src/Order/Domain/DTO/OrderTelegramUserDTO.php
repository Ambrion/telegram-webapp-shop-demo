<?php

declare(strict_types=1);

namespace App\Order\Domain\DTO;

class OrderTelegramUserDTO
{
    public function __construct(
        public string $ulid,

        public ?string $email,

        public int $telegramId,

        public ?string $telegramUserName,
    ) {
    }
}
