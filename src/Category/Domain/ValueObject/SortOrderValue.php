<?php

declare(strict_types=1);

namespace App\Category\Domain\ValueObject;

/**
 * Class SortOrderValue
 * Warning: data value object must be immutable.
 */
readonly class SortOrderValue implements ValueObjectInterface
{
    public function __construct(private ?int $sortOrder = 0)
    {
        if ($sortOrder < 0) {
            throw new \InvalidArgumentException('parentId must be positive integers');
        }
    }

    public function getValue(): ?int
    {
        return $this->sortOrder;
    }

    public function __toString(): string
    {
        return (string) $this->sortOrder;
    }
}
