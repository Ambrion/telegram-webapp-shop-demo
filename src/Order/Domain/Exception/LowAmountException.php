<?php

declare(strict_types=1);

namespace App\Order\Domain\Exception;

class LowAmountException extends \Exception
{
    public function __construct(int $amount, int $code = 0, ?\Throwable $previous = null)
    {
        $message = sprintf('Amount "%d" not integer or must be at least 0.01.', $amount);
        parent::__construct($message, $code, $previous);
    }
}
