<?php

declare(strict_types=1);

namespace App\Payment\Infrastructure\Service;

use App\Payment\Domain\Service\CreatePaymentServiceInterface;
use App\Payment\Infrastructure\Adapter\OrderAdapterInterface;
use App\Payment\Infrastructure\Adapter\UserAdapterInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

readonly class CreatePaymentService implements CreatePaymentServiceInterface
{
    public function __construct(
        private OrderAdapterInterface $orderAdapter,
        private UserAdapterInterface $userAdapter,
        private CreateTelegramPaymentService $createTelegramPayment,
    ) {
    }

    /**
     * @throws \Exception
     */
    public function createPaymentRequest(int $orderId): JsonResponse
    {
        $orderDTO = $this->orderAdapter->findOrderByIdQuery($orderId);

        $userDTO = $this->userAdapter->findUserByUlid($orderDTO->userUlid);

        try {
            $response = $this->createTelegramPayment->sendTelegramPaymentRequest(
                $userDTO->telegramId,
                $orderDTO->invoice,
                $orderDTO->totalAmount,
                $orderDTO->currencyCode,
                $orderDTO->paymentMethod
            );
        } catch (\Exception $e) {
            throw new \Exception('Error sending message from Bot to Client', 0, $e);
        }

        return new JsonResponse([
            'success' => true,
            'details' => $response,
        ], 200);
    }
}
