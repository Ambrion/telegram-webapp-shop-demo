<?php

declare(strict_types=1);

namespace App\Shared\Application\Validation;

use App\Shared\Domain\Validation\QueryValidationIdsInterface;

readonly class QueryValidationIds implements QueryValidationIdsInterface
{
    /**
     * @param array<int, int> $ids
     */
    public function validateArrayIds(array $ids, ?string $message = null): void
    {
        $messageText = 'All IDs must be positive integers and greater then zero';
        if (!empty($message)) {
            $messageText = $message;
        }

        foreach ($ids as $id) {
            if (!is_int($id) || $id <= 0) {
                throw new \InvalidArgumentException($messageText);
            }
        }
    }
}
