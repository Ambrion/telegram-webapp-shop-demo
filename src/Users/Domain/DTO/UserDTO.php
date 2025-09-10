<?php

declare(strict_types=1);

namespace App\Users\Domain\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UserDTO
{
    public function __construct(
        #[Assert\Type('string')]
        public string $ulid,

        #[Assert\NotBlank]
        #[Assert\Type('email')]
        public string $email,

        /** @var string[] */
        #[Assert\Type('array')]
        public array $roles,

        #[Assert\Type('int')]
        public ?int $telegramId,

        #[Assert\Type('string')]
        public ?string $telegramUserName,
    ) {
    }
}
