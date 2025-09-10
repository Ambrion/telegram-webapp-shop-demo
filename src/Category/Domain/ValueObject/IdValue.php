<?php

declare(strict_types=1);

namespace App\Category\Domain\ValueObject;

/**
 * Class IdValue
 * Warning: data value object must be immutable.
 */
readonly class IdValue implements ValueObjectInterface
{
    public function __construct(private ?int $id = null)
    {
        if (!is_null($id)) {
            if ($id <= 0) {
                throw new \InvalidArgumentException('id must be positive integers');
            }
        }
    }

    public function getValue(): ?int
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return (string) $this->id;
    }
}
