<?php

declare(strict_types=1);

namespace App\Order\Domain\DTO;

class OrderListDTO
{
    public function __construct(
        public int $id,

        public string $invoice,

        public int $totalAmount,

        public string $orderStatus,

        public string $createdAt,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'],
            $data['invoice'],
            $data['total_amount'],
            $data['order_status'],
            $data['created_at']
        );
    }
}
