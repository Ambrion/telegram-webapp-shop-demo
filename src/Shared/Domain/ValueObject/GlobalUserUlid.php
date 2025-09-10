<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

readonly class GlobalUserUlid implements ValueObjectInterface
{
    public function __construct(private string $ulid)
    {
        if (empty(trim($ulid))) {
            throw new \InvalidArgumentException('Ulid cannot be empty');
        }

        if (26 !== mb_strlen(trim($ulid))) {
            throw new \InvalidArgumentException('Ulid must be 26 characters');
        }
    }

    public function getValue(): string
    {
        return $this->ulid;
    }
}
