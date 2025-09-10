<?php

declare(strict_types=1);

namespace App\Product\Domain\ValueObject;

readonly class ImagesValue implements ValueObjectArrayInterface
{
    /**
     * @param array<string|mixed>|null $images
     */
    public function __construct(private ?array $images = null)
    {
    }

    public function getElements(): array
    {
        return $this->images;
    }

    public function getElement(int $index): ?int
    {
        return $this->images[$index] ?? null;
    }

    public function contains(string $value): bool
    {
        return in_array($value, $this->images);
    }
}
