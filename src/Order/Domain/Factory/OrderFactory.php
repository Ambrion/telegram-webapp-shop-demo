<?php

declare(strict_types=1);

namespace App\Order\Domain\Factory;

use App\Order\Domain\Entity\Order;
use App\Order\Domain\Model\OrderProduct;
use App\Order\Domain\ValueObject\CurrencyCodeValue;
use App\Order\Domain\ValueObject\InvoiceValue;
use App\Order\Domain\ValueObject\OrderStatusIdValue;
use App\Order\Domain\ValueObject\PaymentMethodValue;
use App\Order\Domain\ValueObject\ProductsValue;
use App\Order\Domain\ValueObject\TotalAmountValue;
use App\Order\Domain\ValueObject\UlidValue;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

readonly class OrderFactory
{
    public function __construct(private ParameterBagInterface $parameterBag)
    {
    }

    /**
     * @param array<OrderProduct> $products
     */
    public function create(
        string $userUlid,
        string $currencyCode,
        int $totalAmount,
        string $paymentMethod,
        int $orderStatusId,
        array $products,
    ): Order {
        $order = new Order();

        $ulidValue = new UlidValue($userUlid);
        $order->setUserUlid($ulidValue);

        $invoice = uniqid($this->parameterBag->get('invoice_prefix'));

        $invoiceValue = new InvoiceValue($invoice);
        $order->setInvoice($invoiceValue);

        $currencyCodeValue = new CurrencyCodeValue($currencyCode);
        $order->setCurrencyCode($currencyCodeValue);

        $totalAmountValue = new TotalAmountValue($totalAmount);
        $order->setTotalAmount($totalAmountValue);

        $paymentMethodValue = new PaymentMethodValue($paymentMethod);
        $order->setPaymentMethod($paymentMethodValue);

        $orderStatusIdValue = new OrderStatusIdValue($orderStatusId);
        $order->setOrderStatusId($orderStatusIdValue);

        $productsValue = new ProductsValue($products);
        $order->setProducts($productsValue);

        return $order;
    }
}
