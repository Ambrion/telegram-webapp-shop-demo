<?php

declare(strict_types=1);

namespace App\Order\Domain\DTO;

use App\Shared\Domain\DTO\OrderProductDTO;

class OrderDTO
{
    public function __construct(
        public int $id,

        public string $invoice,

        public string $paymentMethod,

        public string $currencyCode,

        public int $totalAmount,

        public string $orderStatus,

        public ?string $providerPaymentChargeId,

        public string $createdAt,

        /**
         * @var array<OrderProductDTO>
         */
        public array $products,

        public string $userUlid,
    ) {
        foreach ($this->products as $product) {
            if (!$product instanceof OrderProductDTO) {
                throw new \InvalidArgumentException('All products must be instances of OrderProductDTO');
            }
        }
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'],
            $data['invoice'],
            $data['payment_method'],
            $data['currency_code'],
            $data['total_amount'],
            $data['order_status'],
            $data['provider_payment_charge_id'],
            $data['created_at'],
            $data['products'],
            $data['user_ulid'],
        );
    }
}
