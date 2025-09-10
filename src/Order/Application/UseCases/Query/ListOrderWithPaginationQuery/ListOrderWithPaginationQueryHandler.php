<?php

declare(strict_types=1);

namespace App\Order\Application\UseCases\Query\ListOrderWithPaginationQuery;

use App\Order\Domain\DTO\OrderListDTO;
use App\Order\Domain\Repository\OrderQueryRepositoryInterface;
use App\Order\Domain\Service\OrderStatus;
use App\Shared\Application\Query\QueryHandlerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

readonly class ListOrderWithPaginationQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private OrderQueryRepositoryInterface $repository,
        private TranslatorInterface $translator,
    ) {
    }

    /**
     * @return array<OrderListDTO>|null
     */
    public function __invoke(ListOrderWithPaginationQuery $query): ?array
    {
        $result = $this->repository->listOrderWithPagination($query->filter, $query->offset, $query->limit);

        if (!$result) {
            return null;
        }

        $orderListDTOs = [];
        foreach ($result as $orderData) {
            $orderData['total_amount'] = $orderData['total_amount'] / 100;
            $orderData['order_status'] = $this->translator->trans(
                OrderStatus::ORDER_STATUS[$orderData['order_status_id']],
                [],
                'admin.order.status'
            );

            $orderListDTOs[] = OrderListDTO::fromArray($orderData);
        }

        return $orderListDTOs;
    }
}
