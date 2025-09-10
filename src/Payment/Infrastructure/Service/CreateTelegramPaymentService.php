<?php

declare(strict_types=1);

namespace App\Payment\Infrastructure\Service;

use App\Payment\Domain\Exception\PaymentTelegramException;
use App\Payment\Domain\Service\CreateTelegramPaymentServiceInterface;
use App\Payment\Infrastructure\Adapter\TelegramAdapterInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

readonly class CreateTelegramPaymentService implements CreateTelegramPaymentServiceInterface
{
    private const string METHOD = 'sendInvoice';

    public function __construct(
        private ParameterBagInterface $parameterBag,
        private TelegramAdapterInterface $telegramAdapter,
    ) {
    }

    /**
     * @throws \Exception
     */
    public function sendTelegramPaymentRequest(int $telegramId, string $invoice, int $totalAmount, string $currencyCode, string $paymentMethod): JsonResponse
    {
        $getProvider = sprintf('telegram_bot_%s_token_provider', strtolower($paymentMethod));

        $providerToken = $this->parameterBag->get($getProvider) ?? null;
        if (empty($providerToken)) {
            throw new PaymentTelegramException('Error: telegram payment bot token provider is null');
        }

        $postData = $this->preparePostData(
            $telegramId,
            $invoice,
            $totalAmount,
            $currencyCode,
            $providerToken
        );

        try {
            $response = $this->telegramAdapter->telegramApiRequest($postData, self::METHOD);
        } catch (\Exception $e) {
            throw new \Exception('Error sending message from Bot to Client', 0, $e);
        }

        return new JsonResponse($response);
    }

    /**
     * @return array<string, mixed>
     */
    private function preparePostData(int $telegramId, string $invoice, int $totalAmount, string $currencyCode, string $providerToken): array
    {
        $prices = [
            [
                'label' => 'Итого к оплате',
                'amount' => $totalAmount, // сумма в копейках
            ],
        ];

        return [
            'chat_id' => $telegramId,
            'title' => 'Покупка товаров в магазине',
            'description' => 'Оплата заказа: '.$invoice,
            'payload' => $invoice,
            'provider_token' => $providerToken,
            'currency' => $currencyCode,
            'prices' => $prices,
            'need_shipping_address' => false,
            'is_flexible' => false,
            'start_parameter' => $invoice,
            'reply_markup' => [
                'inline_keyboard' => [[[
                    'text' => 'К оплате',
                    'pay' => true,
                ]]],
            ],
        ];
    }
}
