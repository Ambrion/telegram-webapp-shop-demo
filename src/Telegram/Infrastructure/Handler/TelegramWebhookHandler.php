<?php

declare(strict_types=1);

namespace App\Telegram\Infrastructure\Handler;

use App\Telegram\Application\Handler\TelegramWebhookHandlerInterface;
use App\Telegram\Application\Validation\TelegramPreCheckoutQueryValidationInterface;
use App\Telegram\Infrastructure\Adapter\OrderAdapterInterface;
use App\Telegram\Infrastructure\Adapter\UserAdapterInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Notifier\Exception\TransportExceptionInterface;

readonly class TelegramWebhookHandler implements TelegramWebhookHandlerInterface
{
    public function __construct(
        private TelegramPreCheckoutQueryValidationInterface $preCheckoutQuery,
        private ParameterBagInterface $parameterBag,
        private UserAdapterInterface $userAdapter,
        private OrderAdapterInterface $orderAdapter,
    ) {
    }

    /**
     * Handle of webhook.
     *
     * @param array<string, mixed> $data
     *
     * @throws TransportExceptionInterface
     * @throws \Exception
     */
    public function handle(array $data): void
    {
        if (!empty($data['message']['text']) && '/start' === $data['message']['text']) {
            $textMessage = "Добро пожаловать в наш магазин!\n";
            $textMessage .= "Нажмите на кнопку 'Каталог' чтобы увидеть наши предложения и сделать заказ.\n";

            $this->sendMessageFromBotToClient($data, $textMessage);
        }

        if (!empty($data['pre_checkout_query']) && !empty($data['pre_checkout_query']['id'])) {
            $validate = $this->preCheckoutQuery->validate($data['pre_checkout_query']);

            if ($validate) {
                $postData = [
                    'pre_checkout_query_id' => $data['pre_checkout_query']['id'],
                    'ok' => true,
                ];

                $this->telegramApiRequest($postData, 'answerPreCheckoutQuery');
            }
        }

        if (!empty($data['message']['successful_payment']) && !empty($data['message']['chat']['id'])) {
            $textMessage = "Благодарим Вас за покупку!\n";
            // $textMessage .= "Наш менеджер свяжется с Вами для уточнения деталей доставки.\n";

            if (!empty($data['message']['successful_payment']['invoice_payload'])) {
                $invoice = (string) $data['message']['successful_payment']['invoice_payload'];

                $textMessage .= '<b>Номер заказа:</b> '.$invoice."\n";

                $userDTO = $this->userAdapter->findUserByTelegramId($data['message']['chat']['id']);

                if ($userDTO->ulid && $data['message']['successful_payment']['provider_payment_charge_id']) {
                    $providerPaymentChargeId = $data['message']['successful_payment']['provider_payment_charge_id'];

                    $this->orderAdapter->updateOrderAfterSuccessfulPayment($userDTO->ulid, $invoice, $providerPaymentChargeId);

                    $managerData['message']['chat']['id'] = $this->parameterBag->get('telegram_bot_manager_notification_telegram_chat_id') ?? null;
                    if ($managerData['message']['chat']['id']) {
                        $managerTextMessage = "Новый заказ в магазине!\n";
                        $managerTextMessage .= '<b>Номер заказа:</b> '.$invoice."\n";

                        if ($data['message']['chat']['username']) {
                            $clientTelegramUserName = $data['message']['chat']['username'];
                            $managerTextMessage .= '<b>Написать клиенту:</b> @'.$clientTelegramUserName."\n";
                        } else {
                            $managerTextMessage .= "С клиентом можно связаться в телеграм через бота, т.к. у него не указано Имя пользователя.\n";
                        }

                        $this->sendMessageFromBotToClient($managerData, $managerTextMessage);
                    }
                }
            }

            $orderManager = $this->parameterBag->get('telegram_bot_order_manager') ?? null;
            if ($data['message']['chat']['username']) {
                $textMessage .= "Наш менеджер свяжется с Вами для уточнения деталей доставки.\n";
            } else {
                $textMessage .= 'За Вами закреплён менеджер: '.$orderManager."\n";
                $textMessage .= "Свяжитесь с ним для уточнения деталей доставки.\n";
            }

            $this->sendMessageFromBotToClient($data, $textMessage);
        }
    }

    /**
     * Telegram API request.
     *
     * @param array<string, mixed> $postData
     */
    public function telegramApiRequest(array $postData, string $method): JsonResponse
    {
        $status = 200;

        $jsonData = json_encode($postData);

        $url = $this->parameterBag->get('telegram_bot_api_url') ?? null;
        $url = $url.$method;

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $jsonData,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);

        $result = curl_exec($ch);

        if (false === $result) {
            return new JsonResponse(['error' => 'cURL error: '.curl_error($ch)], 500);
        }

        $response = json_decode($result, true);
        curl_close($ch);

        if (!$response || !$response['ok']) {
            $data['error'] = 'Telegram returned an error';
            $status = 500;
        }

        $data = [
            'details' => $response,
        ];

        return new JsonResponse(
            $data,
            $status);
    }

    /**
     * Send message from Bot to Client.
     *
     * @param array<string, mixed> $data
     *
     * @throws TransportExceptionInterface
     * @throws \Exception
     */
    private function sendMessageFromBotToClient(array $data, string $textMessage): void
    {
        try {
            $chatId = (string) $data['message']['chat']['id'];

            $postData = [
                'chat_id' => $chatId,
                'text' => $textMessage,
                'parse_mode' => 'HTML',
                'disable_web_page_preview' => true,
                'disable_notification' => true,
            ];

            $this->telegramApiRequest($postData, 'sendMessage');
        } catch (\Exception $e) {
            throw new \Exception('Error sending message from Bot to Client', 0, $e);
        }
    }
}
