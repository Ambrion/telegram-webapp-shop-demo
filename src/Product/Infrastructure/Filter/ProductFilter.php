<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\Filter;

use App\Product\Domain\Filter\ProductFilterInterface;

class ProductFilter implements ProductFilterInterface
{
    private ?string $title = null;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }
}
