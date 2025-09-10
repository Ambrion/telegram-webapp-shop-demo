<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Service;

use App\Order\Domain\Exception\InvoiceException;
use App\Order\Domain\Exception\LowAmountException;
use App\Order\Domain\Service\CreateInvoiceServiceInterface;
use App\Order\Domain\Service\OrderCommandServiceInterface;
use App\Order\Domain\Service\OrderStatus;
use App\Order\Domain\ValueObject\CartUserValue;
use App\Order\Domain\ValueObject\CartValue;
use App\Order\Infrastructure\Adapter\ProductAdapterInterface;
use App\Order\Infrastructure\Adapter\UserAdapterInterface;

readonly class CreateInvoiceService implements CreateInvoiceServiceInterface
{
    public function __construct(
        private UserAdapterInterface $userAdapter,
        private ProductAdapterInterface $productAdapter,
        private OrderCommandServiceInterface $orderCommandService,
    ) {
    }

    /**
     * @throws LowAmountException
     * @throws InvoiceException
     */
    public function createInvoice(array $cart, array $cartUser): int
    {
        $amount = 0;

        $cartValue = new CartValue($cart);
        $cartUserValue = new CartUserValue($cartUser);

        $telegramId = $cartUserValue->getByKey('id');
        $telegramUsername = $cartUserValue->getByKey('username');

        $orderProductDTO = $this->productAdapter->createProductFromCart($cartValue->getElements());

        if (empty($orderProductDTO)) {
            throw new InvoiceException('Error: cannot create order product from cart.');
        }

        foreach ($orderProductDTO as $item) {
            $amount += $item->totalPrice;
        }

        if (!is_int($amount) || $amount < 1) {
            throw new LowAmountException($amount);
        }

        $userDTO = $this->userAdapter->findUserByTelegramId($telegramId);

        if (empty($userDTO)) {
            $fakeEmail = uniqid('fake.email_');
            $email = $fakeEmail.'@bot.thearttoprovide.name';

            $globalUserUlid = $this->userAdapter->createUser($email, null, $telegramId, $telegramUsername);
            $ulid = $globalUserUlid->getValue();
        } else {
            $ulid = $userDTO->ulid;
        }

        $currency = 'RUB';
        $totalAmount = (int) round($amount * 100);
        $orderStatusId = OrderStatus::ORDER_STATUS_NEW;

        $providerData = $this->getPaymentProviderData();

        $orderId = $this->orderCommandService->createOrder($ulid, $currency, $totalAmount, $providerData['paymentMethod'], $orderStatusId, $orderProductDTO);
        if (empty($orderId)) {
            throw new InvoiceException('Error: invoice not created');
        }

        return $orderId;
    }

    /**
     * @return array<string, mixed>
     */
    private function getPaymentProviderData(?int $providerId = null): array
    {
        $result = [];
        // TODO Эмулируем выбор провайдера пришедшего с фронта. Работаем с провайдером по умолчанию.
        if (empty($providerId)) {
            $result['paymentMethod'] = 'YOUKASSA';
        }

        return $result;
    }
}
