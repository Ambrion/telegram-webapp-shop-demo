<?php

declare(strict_types=1);

namespace App\Product\Domain\ValueObject;

/**
 * Interface ValueObjectInterface.
 */
interface ValueObjectInterface
{
    public function getValue(): mixed;

    public function __toString(): string;
}
