<?php

declare(strict_types=1);

namespace App\Shared\Domain\Validation;

interface QueryValidationIdsInterface
{
    /**
     * Validate array ids.
     *
     * @param array<int, int> $ids
     */
    public function validateArrayIds(array $ids, ?string $message = null): void;
}
