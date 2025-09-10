<?php

declare(strict_types=1);

namespace App\Order\Domain\ValueObject;

/**
 * Interface ValueObjectInterface.
 */
interface ValueObjectArrayInterface
{
    /**
     * @return array<string, mixed>
     */
    public function getElements(): array;

    public function getElement(int $index): ?int;

    public function contains(string $value): bool;

    public function getByKey(string $key): mixed;
}
