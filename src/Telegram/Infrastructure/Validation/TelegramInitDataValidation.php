<?php

declare(strict_types=1);

namespace App\Telegram\Infrastructure\Validation;

use App\Telegram\Application\Validation\TelegramInitDataValidationInterface;
use App\Telegram\Domain\Exception\TelegramException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

readonly class TelegramInitDataValidation implements TelegramInitDataValidationInterface
{
    private string $telegramPublicKeyTest;
    private string $telegramPublicKeyProd;
    private string $botToken;

    public function __construct(private ParameterBagInterface $parameterBag)
    {
        $this->telegramPublicKeyTest = $this->parameterBag->get('telegram_public_key_test') ?? null;
        $this->telegramPublicKeyProd = $this->parameterBag->get('telegram_public_key_prod') ?? null;
        $this->botToken = $this->parameterBag->get('telegram_bot_token') ?? null;
    }

    /**
     * Validate telegram incoming initData.
     *
     * @param array<string, mixed> $params The initData from Telegram.WebApp.initData
     *
     * @throws \SodiumException
     * @throws TelegramException
     */
    public function validateTelegramIncomingData(array $params): void
    {
        if (!$this->validateInitData($params, $this->botToken)) {
            throw new TelegramException('Invalid initData');
        }

        $this->validateTelegramUser($params);
    }

    /**
     * Validate Telegram Web App initData.
     *
     * @param array<string, mixed> $params            The initData from Telegram.WebApp.initData
     * @param string               $botToken          Your bot token
     * @param bool                 $isTestEnvironment Whether to use test environment key
     *
     * @return bool True if data is valid, false otherwise
     *
     * @throws \SodiumException
     */
    public function validateInitData(array $params, string $botToken, bool $isTestEnvironment = false): bool
    {
        // Check required parameters
        if (!isset($params['signature']) || !isset($params['auth_date'])) {
            return false;
        }

        $explodeToken = explode(':', $botToken);
        $botId = $explodeToken[0];

        // Save the signature and remove hash and signature from params
        $signature = $params['signature'];
        unset($params['hash'], $params['signature']);

        // Create data-check-string
        $dataCheckString = $this->createDataCheckString($params, $botId);

        // Get appropriate public key
        $publicKeyHex = $isTestEnvironment ? $this->telegramPublicKeyTest : $this->telegramPublicKeyProd;
        $publicKey = hex2bin($publicKeyHex);

        // Decode signature from base64url
        $decodedSignature = $this->base64urlDecode($signature);

        // Verify signature using Ed25519
        return sodium_crypto_sign_verify_detached($decodedSignature, $dataCheckString, $publicKey);
    }

    /**
     * Create data-check-string according to Telegram documentation.
     *
     * @param array<string, mixed> $params Parameters from initData (without hash and signature)
     * @param string               $botId  Bot ID
     *
     * @return string Data-check-string
     */
    private function createDataCheckString(array $params, string $botId): string
    {
        // Sort parameters alphabetically
        ksort($params);

        // Create key=value pairs
        $keyValuePairs = [];
        foreach ($params as $key => $value) {
            $keyValuePairs[] = $key.'='.$value;
        }

        // Construct data-check-string
        return $botId.':WebAppData'."\n".implode("\n", $keyValuePairs);
    }

    /**
     * Decode base64url encoded string.
     *
     * @param string $data Base64url encoded data
     *
     * @return string Decoded data
     */
    private function base64urlDecode(string $data): string
    {
        $data = str_replace(['-', '_'], ['+', '/'], $data);
        $padding = strlen($data) % 4;
        if (0 !== $padding) {
            $data .= str_repeat('=', 4 - $padding);
        }

        return base64_decode($data);
    }

    /**
     * Validate telegram user.
     *
     * @param array<string, mixed> $params
     *
     * @throws TelegramException
     */
    public function validateTelegramUser(array $params): void
    {
        if (!isset($params['user'])) {
            throw new TelegramException('User data missing');
        } else {
            $userData = json_decode($params['user'], true);

            if (JSON_ERROR_NONE !== json_last_error()) {
                throw new TelegramException('Invalid user JSON');
            }

            $requiredFields = ['id'];
            foreach ($requiredFields as $field) {
                if (!isset($userData[$field])) {
                    throw new TelegramException("Missing field: $field");
                }
            }
        }
    }
}
