<?php

declare(strict_types=1);

namespace App\Product\Domain\Repository;

use App\Product\Domain\Entity\Product;
use Doctrine\DBAL\Exception;

interface ProductCommandRepositoryInterface
{
    /**
     * Add product.
     *
     * @throws Exception
     */
    public function add(Product $product): int;

    /**
     * Update product.
     *
     * @throws \Exception
     */
    public function update(Product $product): int;
}
