<?php

declare(strict_types=1);

namespace App\Product\Domain\ValueObject;

readonly class DescriptionValue implements ValueObjectInterface
{
    public function __construct(private ?string $description = null)
    {
    }

    public function getValue(): ?string
    {
        return $this->description;
    }

    public function __toString(): string
    {
        return $this->description;
    }
}
