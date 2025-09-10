<?php

declare(strict_types=1);

namespace App\Order\Domain\Exception;

class InvoiceException extends \Exception
{
    public function __construct(string $message = '', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
