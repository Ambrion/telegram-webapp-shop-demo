<?php

declare(strict_types=1);

namespace App\Order\Domain\Entity;

use App\Order\Domain\ValueObject\CurrencyCodeValue;
use App\Order\Domain\ValueObject\IdValue;
use App\Order\Domain\ValueObject\InvoiceValue;
use App\Order\Domain\ValueObject\OrderStatusIdValue;
use App\Order\Domain\ValueObject\PaymentMethodValue;
use App\Order\Domain\ValueObject\ProductsValue;
use App\Order\Domain\ValueObject\TotalAmountValue;
use App\Order\Domain\ValueObject\UlidValue;

class Order
{
    private IdValue $id;
    private UlidValue $userUlid;
    private InvoiceValue $invoice;
    private CurrencyCodeValue $currencyCode;
    private TotalAmountValue $totalAmount;
    private PaymentMethodValue $paymentMethod;
    private OrderStatusIdValue $orderStatusId;
    private ?string $providerPaymentChargeId = null;
    private ProductsValue $products;
    private string $createdAt;
    private ?string $updatedAt = null;

    public function getId(): IdValue
    {
        return $this->id;
    }

    public function setId(IdValue $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getUserUlid(): UlidValue
    {
        return $this->userUlid;
    }

    public function setUserUlid(UlidValue $userUlid): static
    {
        $this->userUlid = $userUlid;

        return $this;
    }

    public function getInvoice(): InvoiceValue
    {
        return $this->invoice;
    }

    public function setInvoice(InvoiceValue $invoice): static
    {
        $this->invoice = $invoice;

        return $this;
    }

    public function getCurrencyCode(): CurrencyCodeValue
    {
        return $this->currencyCode;
    }

    public function setCurrencyCode(CurrencyCodeValue $currencyCode): static
    {
        $this->currencyCode = $currencyCode;

        return $this;
    }

    public function getTotalAmount(): TotalAmountValue
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(TotalAmountValue $totalAmount): static
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }

    public function getPaymentMethod(): PaymentMethodValue
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(PaymentMethodValue $paymentMethod): static
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    public function getOrderStatusId(): OrderStatusIdValue
    {
        return $this->orderStatusId;
    }

    public function setOrderStatusId(OrderStatusIdValue $orderStatusId): static
    {
        $this->orderStatusId = $orderStatusId;

        return $this;
    }

    public function getProviderPaymentChargeId(): ?string
    {
        return $this->providerPaymentChargeId;
    }

    public function setProviderPaymentChargeId(?string $providerPaymentChargeId): static
    {
        $this->providerPaymentChargeId = $providerPaymentChargeId;

        return $this;
    }

    public function getProducts(): ProductsValue
    {
        return $this->products;
    }

    public function setProducts(ProductsValue $products): static
    {
        $this->products = $products;

        return $this;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?string $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
