<?php

declare(strict_types=1);

namespace App\Order\Domain\Service;

interface CreateInvoiceServiceInterface
{
    /**
     * @param array<string, mixed> $cart
     * @param array<string, mixed> $cartUser
     */
    public function createInvoice(array $cart, array $cartUser): int;
}
