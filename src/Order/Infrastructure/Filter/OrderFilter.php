<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Filter;

use App\Order\Domain\Filter\OrderFilterInterface;

class OrderFilter implements OrderFilterInterface
{
    private ?string $invoice = null;

    public function getInvoice(): ?string
    {
        return $this->invoice;
    }

    public function setInvoice(?string $invoice): static
    {
        $this->invoice = $invoice;

        return $this;
    }
}
