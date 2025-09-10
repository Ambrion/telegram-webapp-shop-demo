<?php

declare(strict_types=1);

namespace App\Order\Application\UseCases\Command\CreateOrder;

use App\Order\Domain\Exception\InvoiceException;
use App\Order\Domain\Factory\OrderFactory;
use App\Order\Domain\Model\OrderProduct;
use App\Order\Domain\Repository\OrderCommandRepositoryInterface;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\DTO\OrderProductDTO;

readonly class CreateOrderCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private OrderCommandRepositoryInterface $repository,
        private OrderFactory $orderFactory,
    ) {
    }

    /**
     * @throws InvoiceException
     */
    public function __invoke(CreateOrderCommand $orderCommand): int
    {
        $domainProducts = array_map(
            fn (OrderProductDTO $product) => new OrderProduct(
                productId: $product->productId,
                title: $product->title,
                quantity: $product->quantity,
                price: $product->price,
                totalPrice: $product->totalPrice
            ),
            $orderCommand->products
        );

        $order = $this->orderFactory->create(
            $orderCommand->userUlid,
            $orderCommand->currencyCode,
            $orderCommand->totalAmount,
            $orderCommand->paymentMethod,
            $orderCommand->orderStatusId,
            $domainProducts
        );

        $orderId = $this->repository->add($order);

        if (empty($orderId)) {
            throw new InvoiceException('Error: the order was not created.');
        }

        return $orderId;
    }
}
