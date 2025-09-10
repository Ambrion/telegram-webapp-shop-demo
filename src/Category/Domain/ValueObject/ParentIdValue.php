<?php

declare(strict_types=1);

namespace App\Category\Domain\ValueObject;

/**
 * Class ParentIdValue
 * Warning: data value object must be immutable.
 */
readonly class ParentIdValue implements ValueObjectInterface
{
    public function __construct(private ?int $parentId = 0)
    {
        if ($parentId < 0) {
            throw new \InvalidArgumentException('parentId must be positive integers');
        }
    }

    public function getValue(): ?int
    {
        return $this->parentId;
    }

    public function __toString(): string
    {
        return (string) $this->parentId;
    }
}
