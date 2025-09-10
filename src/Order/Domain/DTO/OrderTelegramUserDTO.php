<?php

declare(strict_types=1);

namespace App\Order\Domain\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class OrderTelegramUserDTO
{
    public function __construct(
        #[Assert\Type('string')]
        public string $ulid,

        #[Assert\Type('email')]
        public ?string $email,

        #[Assert\NotBlank]
        #[Assert\Type('int')]
        public int $telegramId,

        #[Assert\Type('string')]
        public ?string $telegramUserName,
    ) {
    }
}
