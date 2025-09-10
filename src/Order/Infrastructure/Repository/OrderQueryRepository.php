<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Repository;

use App\Order\Domain\Entity\Order;
use App\Order\Domain\Filter\OrderFilterInterface;
use App\Order\Domain\Repository\OrderQueryRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 */
class OrderQueryRepository extends ServiceEntityRepository implements OrderQueryRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    /**
     * List order with filter and pagination.
     *
     * @return array<array<string, mixed>>
     *
     * @throws Exception
     */
    public function listOrderWithPagination(OrderFilterInterface $orderFilter, int $offset, int $limit): array
    {
        $addWhere = '';

        if ($orderFilter->getInvoice()) {
            $addWhere .= " AND invoice LIKE CONCAT('%', :searchValue, '%')";
        }

        $sql = "SELECT id,
                       invoice,
                       total_amount,
                       order_status_id,
                       created_at
                FROM order_order
                WHERE 1
                $addWhere
                ORDER BY id DESC
                LIMIT :limit OFFSET :offset

        ";

        $stmt = $this->getEntityManager()
            ->getConnection()
            ->prepare($sql);

        if (!empty($orderFilter->getInvoice())) {
            $stmt->bindValue('searchValue', $orderFilter->getInvoice(), \PDO::PARAM_STR);
        }

        $stmt->bindValue('limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue('offset', $offset, \PDO::PARAM_INT);

        return $stmt->executeQuery()->fetchAllAssociative();
    }

    /**
     * Count all order with filter.
     *
     * @throws Exception
     */
    public function countAll(OrderFilterInterface $orderFilter): int
    {
        $addWhere = '';

        if ($orderFilter->getInvoice()) {
            $addWhere .= " AND invoice LIKE CONCAT('%', :searchValue, '%')";
        }

        $sql = "SELECT COUNT(*) AS total
                FROM order_order
                WHERE 1
                $addWhere
                ";

        $stmt = $this->getEntityManager()
            ->getConnection()
            ->prepare($sql);

        if (!empty($orderFilter->getInvoice())) {
            $stmt->bindValue('searchValue', $orderFilter->getInvoice(), \PDO::PARAM_STR);
        }

        return (int) $stmt->executeQuery()->fetchOne();
    }

    /**
     * Find one order by id.
     *
     * @return array<string, mixed>
     *
     * @throws Exception
     */
    public function findOneById(int $id): array
    {
        $sql = 'SELECT id,
                       user_ulid,
                       invoice,
                       currency_code,
                       total_amount,
                       payment_method,
                       order_status_id,
                       created_at,
                       provider_payment_charge_id
                FROM order_order
                WHERE id = :id
                ';

        $stmt = $this->getEntityManager()
            ->getConnection()
            ->prepare($sql);

        $stmt->bindValue('id', $id, \PDO::PARAM_INT);

        return $stmt->executeQuery()->fetchAssociative();
    }

    /**
     * Find order products.
     *
     * @return array<array<string, mixed>>
     *
     * @throws Exception
     */
    public function findOrderProduct(int $orderId): array
    {
        $sql = 'SELECT oop.product_id,
                       oop.title,
                       oop.quantity,
                       oop.price,
                       oop.total_price,
                       MIN(pi.file_path) AS file_path
                FROM order_order_product oop
                    LEFT JOIN product_product_image pi
                        ON oop.product_id = pi.product_id
                            AND pi.sort_order = 0
                WHERE order_id = :order_id
                GROUP BY oop.product_id
                ';

        $stmt = $this->getEntityManager()
            ->getConnection()
            ->prepare($sql);

        $stmt->bindValue('order_id', $orderId, \PDO::PARAM_INT);

        return $stmt->executeQuery()->fetchAllAssociative();
    }
}
