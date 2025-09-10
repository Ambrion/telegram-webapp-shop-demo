<?php

declare(strict_types=1);

namespace App\Product\Domain\Filter;

interface ProductFilterInterface
{
    public function getTitle(): ?string;

    public function setTitle(?string $title): static;
}
