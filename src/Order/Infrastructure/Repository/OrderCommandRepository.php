<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Repository;

use App\Order\Domain\Entity\Order;
use App\Order\Domain\Repository\OrderCommandRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 */
class OrderCommandRepository extends ServiceEntityRepository implements OrderCommandRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    /**
     * Add order.
     *
     * @throws \Exception
     */
    public function add(Order $order): int
    {
        $connection = $this->getEntityManager()->getConnection();

        try {
            $connection->beginTransaction();

            $sql = 'INSERT INTO order_order (
                        user_ulid,
                        invoice,
                        currency_code,
                        total_amount,
                        payment_method,
                        order_status_id
                    ) VALUES (
                        :user_ulid,
                        :invoice,
                        :currency_code,
                        :total_amount,
                        :payment_method,
                        :order_status_id
                    )';

            $stmt = $connection->prepare($sql);

            $stmt->bindValue('user_ulid', $order->getUserUlid()->getValue(), \PDO::PARAM_STR);
            $stmt->bindValue('invoice', $order->getInvoice()->getValue(), \PDO::PARAM_STR);
            $stmt->bindValue('currency_code', $order->getCurrencyCode()->getValue(), \PDO::PARAM_STR);
            $stmt->bindValue('total_amount', $order->getTotalAmount()->getValue(), \PDO::PARAM_INT);
            $stmt->bindValue('payment_method', $order->getPaymentMethod()->getValue(), \PDO::PARAM_STR);
            $stmt->bindValue('order_status_id', $order->getOrderStatusId()->getValue(), \PDO::PARAM_INT);

            $stmt->executeStatement();

            $orderId = (int) $this->getEntityManager()->getConnection()->lastInsertId();

            $placeholders = [];
            $values = [];

            foreach ($order->getProducts()->getElements() as $key => $product) {
                $placeholders[] = '(:order_id, :product_id_'.$key.', :title_'.$key.', :quantity_'.$key.', :price_'.$key.', :total_price_'.$key.')';
                $values['product_id_'.$key] = $product->productId;
                $values['title_'.$key] = $product->title;
                $values['quantity_'.$key] = $product->quantity;
                $values['price_'.$key] = $product->price;
                $values['total_price_'.$key] = $product->totalPrice;
            }

            $values['order_id'] = $orderId;

            $sql = 'INSERT INTO order_order_product (
                        order_id,
                        product_id,
                        title,
                        quantity,
                        price,
                        total_price
                    ) VALUES '.implode(', ', $placeholders);

            $stmt = $connection->prepare($sql);

            foreach ($values as $key => $value) {
                $stmt->bindValue($key, $value, \PDO::PARAM_STR);
            }

            $stmt->executeStatement();

            $connection->commit();

            return $orderId;
        } catch (Exception $e) {
            if ($connection->isTransactionActive()) {
                $connection->rollBack();
            }

            throw new \Exception('Order creation error', 0, $e);
        }
    }

    /**
     * Updated order after successful payment.
     *
     * @throws Exception
     * @throws \Exception
     */
    public function update(string $userUlid, string $invoice, string $providerPaymentChargeId, int $orderStatusId): int
    {
        $connection = $this->getEntityManager()->getConnection();

        try {
            $connection->beginTransaction();

            $sql = 'UPDATE order_order
                    SET order_status_id = :order_status_id,
                        provider_payment_charge_id = :provider_payment_charge_id,
                        updated_at = NOW()
                    WHERE user_ulid = :user_ulid
                        AND invoice = :invoice
                    ';

            $stmt = $connection->prepare($sql);

            $stmt->bindValue('user_ulid', $userUlid, \PDO::PARAM_STR);
            $stmt->bindValue('invoice', $invoice, \PDO::PARAM_STR);
            $stmt->bindValue('provider_payment_charge_id', $providerPaymentChargeId, \PDO::PARAM_STR);
            $stmt->bindValue('order_status_id', $orderStatusId, \PDO::PARAM_INT);

            $stmt->executeStatement();

            if ($connection->commit()) {
                return 1;
            }

            return 0;
        } catch (Exception $e) {
            if ($connection->isTransactionActive()) {
                $connection->rollBack();
            }

            throw new \Exception('Order status update error', 0, $e);
        }
    }
}
