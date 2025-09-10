<?php

declare(strict_types=1);

namespace App\Order\Domain\ValueObject;

readonly class CartValue implements ValueObjectArrayInterface
{
    /**
     * @param array<string, mixed> $cart
     */
    public function __construct(private array $cart)
    {
        if (empty($cart)) {
            throw new \InvalidArgumentException('Cart cannot be empty.');
        }

        foreach ($cart as $item) {
            if (empty($item['id'])) {
                throw new \InvalidArgumentException('Product id cannot be empty.');
            }

            if (!is_int($item['id'])) {
                throw new \InvalidArgumentException('Product id must be integer.');
            }
        }
    }

    public function getElements(): array
    {
        return $this->cart;
    }

    public function getElement(int $index): ?int
    {
        return $this->cart[$index] ?? null;
    }

    public function contains(string $value): bool
    {
        return in_array($value, $this->cart);
    }

    public function getByKey(string $key): null
    {
        return null;
    }
}
