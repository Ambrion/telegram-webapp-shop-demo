<?php

declare(strict_types=1);

namespace App\Order\Domain\ValueObject;

readonly class PaymentMethodValue
{
    public function __construct(private string $paymentMethod)
    {
        if (empty(trim($paymentMethod))) {
            throw new \InvalidArgumentException('Payment Method code cannot be empty');
        }
    }

    public function getValue(): string
    {
        return $this->paymentMethod;
    }

    public function __toString(): string
    {
        return $this->paymentMethod;
    }
}
