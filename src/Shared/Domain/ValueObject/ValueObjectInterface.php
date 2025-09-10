<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

/**
 * Interface ValueObjectInterface.
 */
interface ValueObjectInterface
{
    public function getValue(): mixed;
}
