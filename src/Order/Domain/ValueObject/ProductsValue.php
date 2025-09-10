<?php

declare(strict_types=1);

namespace App\Order\Domain\ValueObject;

use App\Order\Domain\Model\OrderProduct;

readonly class ProductsValue
{
    /**
     * @param array<OrderProduct> $products
     */
    public function __construct(private array $products)
    {
        if (empty($products)) {
            throw new \InvalidArgumentException('Products cannot be empty.');
        }

        foreach ($products as $item) {
            if (!is_int($item->productId)) {
                throw new \InvalidArgumentException('Product id must be integer.');
            }

            if (empty($item->title)) {
                throw new \InvalidArgumentException('Product title cannot be empty.');
            }

            if (!is_int($item->price)) {
                throw new \InvalidArgumentException('Product price must be integer.');
            }
        }
    }

    /**
     * @return array<OrderProduct>
     */
    public function getElements(): array
    {
        return $this->products;
    }

    public function getElement(int $index): ?OrderProduct
    {
        return $this->products[$index] ?? null;
    }

    public function contains(string $value): bool
    {
        return in_array($value, $this->products);
    }
}
