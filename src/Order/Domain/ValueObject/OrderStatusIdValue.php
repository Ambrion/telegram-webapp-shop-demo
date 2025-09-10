<?php

declare(strict_types=1);

namespace App\Order\Domain\ValueObject;

readonly class OrderStatusIdValue
{
    public function __construct(private int $orderStatusId)
    {
    }

    public function getValue(): int
    {
        return $this->orderStatusId;
    }

    public function __toString(): string
    {
        return (string) $this->orderStatusId;
    }
}
