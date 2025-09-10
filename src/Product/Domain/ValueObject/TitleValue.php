<?php

declare(strict_types=1);

namespace App\Product\Domain\ValueObject;

/**
 * Class TitleValue
 * Warning: data value object must be immutable.
 */
readonly class TitleValue implements ValueObjectInterface
{
    public function __construct(private ?string $title = null)
    {
        if (empty(trim($title))) {
            throw new \InvalidArgumentException('Title cannot be empty');
        }

        if (mb_strlen(trim($title)) > 255) {
            throw new \InvalidArgumentException('Title cannot be longer than 255 characters');
        }
    }

    public function getValue(): ?string
    {
        return $this->title;
    }

    public function __toString(): string
    {
        return $this->title;
    }
}
