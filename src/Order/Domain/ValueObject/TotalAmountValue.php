<?php

declare(strict_types=1);

namespace App\Order\Domain\ValueObject;

readonly class TotalAmountValue
{
    public function __construct(private int $totalAmount)
    {
        if ($totalAmount <= 0) {
            throw new \InvalidArgumentException('Total Amount must be positive integers');
        }
    }

    public function getValue(): int
    {
        return $this->totalAmount;
    }

    public function __toString(): string
    {
        return (string) $this->totalAmount;
    }
}
