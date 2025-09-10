<?php

declare(strict_types=1);

namespace App\Product\Domain\ValueObject;

/**
 * Class ActiveValue
 * Warning: data value object must be immutable.
 */
readonly class ActiveValue implements ValueObjectInterface
{
    public function __construct(private bool $isActive = false)
    {
    }

    public function getValue(): bool
    {
        return $this->isActive;
    }

    public function __toString(): string
    {
        return (string) $this->isActive;
    }
}
