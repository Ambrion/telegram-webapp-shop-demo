<?php

declare(strict_types=1);

namespace App\Product\Domain\ValueObject;

/**
 * Class SlugValue
 * Warning: data value object must be immutable.
 */
readonly class SlugValue implements ValueObjectInterface
{
    public function __construct(private ?string $slug = null)
    {
        if (empty(trim($slug))) {
            throw new \InvalidArgumentException('slug cannot be empty');
        }

        if (mb_strlen(trim($slug)) > 255) {
            throw new \InvalidArgumentException('slug cannot be longer than 255 characters');
        }
    }

    public function getValue(): ?string
    {
        return $this->slug;
    }

    public function __toString(): string
    {
        return $this->slug;
    }
}
