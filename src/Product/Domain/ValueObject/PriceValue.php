<?php

declare(strict_types=1);

namespace App\Product\Domain\ValueObject;

/**
 * Class PriceValue
 * Warning: data value object must be immutable.
 */
readonly class PriceValue implements ValueObjectInterface
{
    public function __construct(private ?int $price = null)
    {
        if (!is_int($price)) {
            throw new \InvalidArgumentException('price must be integer');
        }

        if ($price <= 0) {
            throw new \InvalidArgumentException('price must be positive integers');
        }
    }

    public function getValue(): ?int
    {
        return $this->price;
    }

    public function __toString(): string
    {
        return (string) $this->price;
    }
}
