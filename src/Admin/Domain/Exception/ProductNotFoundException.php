<?php

declare(strict_types=1);

namespace App\Admin\Domain\Exception;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductNotFoundException extends NotFoundHttpException
{
    public function __construct(int $productId, ?\Throwable $previous = null)
    {
        $message = sprintf('Product with ID "%d" not found.', $productId);
        parent::__construct($message, $previous);
    }
}
