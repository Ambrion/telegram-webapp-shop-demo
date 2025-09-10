<?php

declare(strict_types=1);

namespace App\Order\Domain\ValueObject;

readonly class InvoiceValue
{
    public function __construct(private string $invoice)
    {
        if (empty(trim($invoice))) {
            throw new \InvalidArgumentException('Invoice cannot be empty');
        }
    }

    public function getValue(): string
    {
        return $this->invoice;
    }

    public function __toString(): string
    {
        return $this->invoice;
    }
}
