<?php

declare(strict_types=1);

namespace App\Order\Domain\Service;

class OrderStatus
{
    public const int ORDER_STATUS_ABANDONED_CART = 0;
    public const int ORDER_STATUS_NEW = 1;
    public const int ORDER_STATUS_PAYMENT_SUCCESS = 2;

    public const array ORDER_STATUS = [
        self::ORDER_STATUS_ABANDONED_CART => 'abandoned_cart',
        self::ORDER_STATUS_NEW => 'new',
        self::ORDER_STATUS_PAYMENT_SUCCESS => 'payment_success',
    ];
}
