<?php

declare(strict_types=1);

namespace App\Order\Domain\ValueObject;

readonly class CartUserValue implements ValueObjectArrayInterface
{
    /**
     * @param array<string, mixed> $cartUser
     */
    public function __construct(private array $cartUser)
    {
        if (empty($cartUser)) {
            throw new \InvalidArgumentException('User data cannot be empty.');
        }

        if (empty($cartUser['id'])) {
            throw new \InvalidArgumentException('User id cannot be empty.');
        }

        if (!is_int($cartUser['id'])) {
            throw new \InvalidArgumentException('User id must be integer.');
        }
    }

    public function getElements(): array
    {
        return $this->cartUser;
    }

    public function getElement(int $index): ?int
    {
        return $this->cartUser[$index] ?? null;
    }

    public function contains(string $value): bool
    {
        return in_array($value, $this->cartUser);
    }

    public function getByKey(string $key): mixed
    {
        return $this->cartUser[$key] ?? null;
    }
}
