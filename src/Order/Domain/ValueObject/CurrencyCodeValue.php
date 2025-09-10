<?php

declare(strict_types=1);

namespace App\Order\Domain\ValueObject;

readonly class CurrencyCodeValue
{
    public function __construct(private string $currencyCode)
    {
        if (empty(trim($currencyCode))) {
            throw new \InvalidArgumentException('Currency code cannot be empty');
        }

        if (3 !== mb_strlen(trim($currencyCode))) {
            throw new \InvalidArgumentException('Currency code must be 3 characters (by ISO 4217)');
        }
    }

    public function getValue(): ?string
    {
        return $this->currencyCode;
    }

    public function __toString(): string
    {
        return $this->currencyCode;
    }
}
