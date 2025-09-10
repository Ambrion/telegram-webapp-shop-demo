<?php

declare(strict_types=1);

namespace App\Order\Application\UseCases\Query\FindOrderById;

use App\Order\Domain\DTO\OrderDTO;
use App\Order\Domain\Repository\OrderQueryRepositoryInterface;
use App\Order\Domain\Service\OrderStatus;
use App\Shared\Application\Query\QueryHandlerInterface;
use App\Shared\Domain\DTO\OrderProductDTO;
use Symfony\Contracts\Translation\TranslatorInterface;

readonly class FindOrderByIdQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private OrderQueryRepositoryInterface $orderRepository,
        private TranslatorInterface $translator,
    ) {
    }

    public function __invoke(FindOrderByIdQuery $query): ?OrderDTO
    {
        $order = $this->orderRepository->findOneById($query->id);

        if (!$order) {
            return null;
        }

        $order['order_status'] = $this->translator->trans(
            OrderStatus::ORDER_STATUS[$order['order_status_id']],
            [],
            'admin.order.status'
        );

        $products = $this->orderRepository->findOrderProduct($query->id);

        if (!$products) {
            return null;
        }

        foreach ($products as $productData) {
            $order['products'][] = OrderProductDTO::fromArray($productData);
        }

        return OrderDTO::fromArray($order);
    }
}
