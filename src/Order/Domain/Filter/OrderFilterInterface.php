<?php

declare(strict_types=1);

namespace App\Order\Domain\Filter;

interface OrderFilterInterface
{
    public function getInvoice(): ?string;

    public function setInvoice(?string $invoice): static;
}
