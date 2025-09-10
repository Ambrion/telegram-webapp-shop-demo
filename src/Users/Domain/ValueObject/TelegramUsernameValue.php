<?php

declare(strict_types=1);

namespace App\Users\Domain\ValueObject;

readonly class TelegramUsernameValue implements ValueObjectInterface
{
    private const int MIN_LENGTH = 5;
    private const int MAX_LENGTH = 32;
    private const string VALID_PATTERN = '/^[a-zA-Z0-9_]+$/';

    public function __construct(private ?string $telegramUsername = null)
    {
        if (!is_null($this->telegramUsername)) {
            if (!$this->validate($this->telegramUsername)) {
                throw new \InvalidArgumentException('Telegram username not valid');
            }
        }
    }

    public function getValue(): ?string
    {
        return $this->telegramUsername;
    }

    public function __toString(): string
    {
        return $this->telegramUsername;
    }

    private function validate(string $username): bool
    {
        // Check length constraints
        $length = strlen($username);
        if ($length < self::MIN_LENGTH || $length > self::MAX_LENGTH) {
            return false;
        }

        // Check valid characters (A-z, 0-9, underscores only)
        if (!preg_match(self::VALID_PATTERN, $username)) {
            return false;
        }

        return true;
    }
}
