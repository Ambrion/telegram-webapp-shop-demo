<?php

declare(strict_types=1);

namespace App\Order\Application\UseCases\Command\CreateOrder;

use App\Shared\Application\Command\CommandInterface;
use App\Shared\Domain\DTO\OrderProductDTO;

class CreateOrderCommand implements CommandInterface
{
    /**
     * @param array<OrderProductDTO> $products
     */
    public function __construct(
        public string $userUlid,
        public string $currencyCode,
        public int $totalAmount,
        public string $paymentMethod,
        public int $orderStatusId,
        public array $products,
    ) {
    }
}
