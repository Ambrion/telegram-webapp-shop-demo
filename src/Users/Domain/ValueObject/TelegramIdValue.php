<?php

declare(strict_types=1);

namespace App\Users\Domain\ValueObject;

readonly class TelegramIdValue implements ValueObjectInterface
{
    public function __construct(private ?int $telegramId = null)
    {
        if (!is_null($telegramId)) {
            if ($telegramId <= 0) {
                throw new \InvalidArgumentException('telegramId must be positive integers');
            }
        }
    }

    public function getValue(): ?int
    {
        return $this->telegramId;
    }

    public function __toString(): string
    {
        return (string) $this->telegramId;
    }
}
