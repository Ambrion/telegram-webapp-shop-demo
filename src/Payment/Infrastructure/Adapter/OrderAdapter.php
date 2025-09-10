<?php

declare(strict_types=1);

namespace App\Payment\Infrastructure\Adapter;

use App\Order\Domain\DTO\OrderDTO;
use App\Order\Infrastructure\Api\Public\OrderApiInterface;

readonly class OrderAdapter implements OrderAdapterInterface
{
    public function __construct(private OrderApiInterface $api)
    {
    }

    public function findOrderByIdQuery(int $id): ?OrderDTO
    {
        return $this->api->findOrderByIdQuery($id);
    }
}
