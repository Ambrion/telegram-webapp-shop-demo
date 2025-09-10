<?php

declare(strict_types=1);

namespace App\Telegram\Domain\Exception;

class TelegramException extends \Exception
{
    public function __construct(string $message = '', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
