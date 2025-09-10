<?php

declare(strict_types=1);

namespace App\Front\Presentation\Controller;

use App\Front\Infrastructure\Adapter\OrderAdapterInterface;
use App\Front\Infrastructure\Adapter\PaymentAdapterInterface;
use App\Front\Infrastructure\Adapter\TelegramAdapterInterface;
use App\Front\Presentation\Request\CreateTelegramInvoiceRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class PaymentController extends AbstractController
{
    public function __construct(
        private readonly TelegramAdapterInterface $telegramAdapter,
        private readonly OrderAdapterInterface $orderAdapter,
        private readonly PaymentAdapterInterface $paymentAdapter,
    ) {
    }

    /**
     * @throws \Exception
     */
    #[Route('create-invoice', name: 'createInvoice', methods: ['POST'])]
    public function index(Request $request): JsonResponse
    {
        $requestDTO = new CreateTelegramInvoiceRequest($request);

        $this->telegramAdapter->validateTelegramIncomingData($requestDTO->getInitData());

        $orderId = $this->orderAdapter->createInvoice($requestDTO->getCart(), $requestDTO->getUser());

        if (empty($orderId)) {
            return $this->json(['success' => false, 'message' => 'Invoice is empty']);
        }

        $response = $this->paymentAdapter->createPaymentRequest($orderId);

        return new JsonResponse([
            'status' => 'success',
            'message' => 'Инвойс успешно отправлен пользователю',
            'telegram_response' => $response,
        ], 200);
    }
}
