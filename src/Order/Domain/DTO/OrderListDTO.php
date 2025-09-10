<?php

declare(strict_types=1);

namespace App\Order\Domain\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class OrderListDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Type('int')]
        public int $id,

        #[Assert\NotBlank]
        #[Assert\Type('string')]
        public string $invoice,

        #[Assert\NotBlank]
        #[Assert\Type('int')]
        public int $totalAmount,

        #[Assert\NotBlank]
        #[Assert\Type('string')]
        public string $orderStatus,

        #[Assert\NotBlank]
        #[Assert\Type('string')]
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
