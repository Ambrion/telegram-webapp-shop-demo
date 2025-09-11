<?php

declare(strict_types=1);

namespace App\Users\Domain\DTO;

class UserDTO
{
    public function __construct(
        public string $ulid,

        public string $email,

        /** @var string[] */
        public array $roles,

        public ?int $telegramId,

        public ?string $telegramUserName,
    ) {
    }
}
