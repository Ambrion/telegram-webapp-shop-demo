<?php

declare(strict_types=1);

namespace App\Product\Domain\ValueObject;

readonly class CategoriesValue implements ValueObjectArrayInterface
{
    /**
     * @param array<string|mixed>|null $categories
     */
    public function __construct(private ?array $categories = null)
    {
        if (empty($categories)) {
            throw new \InvalidArgumentException('Categories cannot be empty.');
        }

        foreach ($categories as $item) {
            if (!is_int($item)) {
                throw new \InvalidArgumentException('All elements must be integer.');
            }
        }
    }

    public function getElements(): array
    {
        return $this->categories;
    }

    public function getElement(int $index): ?int
    {
        return $this->categories[$index] ?? null;
    }

    public function contains(string $value): bool
    {
        return in_array($value, $this->categories);
    }
}
