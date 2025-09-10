<?php

declare(strict_types=1);

namespace App\Admin\Domain\Exception;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryNotFoundException extends NotFoundHttpException
{
    public function __construct(int $categoryId, ?\Throwable $previous = null)
    {
        $message = sprintf('Category with ID "%d" not found.', $categoryId);
        parent::__construct($message, $previous);
    }
}
