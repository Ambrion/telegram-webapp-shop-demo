<?php

declare(strict_types=1);

namespace App\Order\Domain\DTO;

use App\Shared\Domain\DTO\OrderProductDTO;
use Symfony\Component\Validator\Constraints as Assert;

class OrderDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Type('int')]
        public int $id,

        #[Assert\NotBlank]
        #[Assert\Type('string')]
        public string $invoice,

        #[Assert\NotBlank]
        #[Assert\Type('string')]
        public string $paymentMethod,

        #[Assert\NotBlank]
        #[Assert\Type('string')]
        public string $currencyCode,

        #[Assert\NotBlank]
        #[Assert\Type('int')]
        public int $totalAmount,

        #[Assert\NotBlank]
        #[Assert\Type('string')]
        public string $orderStatus,

        #[Assert\Type('string')]
        public ?string $providerPaymentChargeId,

        #[Assert\NotBlank]
        #[Assert\Type('string')]
        public string $createdAt,

        /**
         * @var array<OrderProductDTO>
         */
        #[Assert\NotBlank]
        #[Assert\Type('array')]
        public array $products,

        #[Assert\NotBlank]
        #[Assert\Type('string')]
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
