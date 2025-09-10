<?php

declare(strict_types=1);

namespace App\Payment\Infrastructure\Adapter;

use App\Order\Domain\DTO\OrderDTO;

interface OrderAdapterInterface
{
    /**
     * Find order by id.
     */
    public function findOrderByIdQuery(int $id): ?OrderDTO;
}
