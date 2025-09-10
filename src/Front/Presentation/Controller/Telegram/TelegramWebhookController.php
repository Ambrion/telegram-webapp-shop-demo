<?php

declare(strict_types=1);

namespace App\Front\Presentation\Controller\Telegram;

use App\Front\Infrastructure\Adapter\TelegramAdapterInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Notifier\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Attribute\Route;

readonly class TelegramWebhookController
{
    public function __construct(private TelegramAdapterInterface $telegramAdapter)
    {
    }

    /**
     * @throws TransportExceptionInterface
     */
    #[Route('/telegram/webhook', name: 'telegram_webhook', methods: ['POST'])]
    public function webhookAction(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // file_put_contents('telegramPOST.txt', print_r($data, true), FILE_APPEND);

        $this->telegramAdapter->handle($data);

        return new JsonResponse('OK');
    }
}
