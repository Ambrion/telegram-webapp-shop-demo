<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Adapter;

use App\Product\Infrastructure\Api\ProductApiInterface;
use App\Shared\Domain\DTO\OrderProductDTO;

readonly class ProductAdapter implements ProductAdapterInterface
{
    public function __construct(private ProductApiInterface $api)
    {
    }

    /**
     * Create products from cart.
     *
     * @param array<string, mixed> $cart
     *
     * @return OrderProductDTO[]|null
     */
    public function createProductFromCart(array $cart): ?array
    {
        $data = $this->api->createProductFromCart($cart);

        if (empty($data)) {
            return null;
        }

        $DTOs = [];

        foreach ($data as $item) {
            $DTOs[] = new OrderProductDTO(
                productId: $item->productId,
                title: $item->title,
                quantity: $item->quantity,
                price: $item->price,
                totalPrice: $item->totalPrice,
                filePath: $item->filePath
            );
        }

        return $DTOs;
    }
}
