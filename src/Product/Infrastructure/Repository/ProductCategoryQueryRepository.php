<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\Repository;

use App\Product\Domain\Entity\ProductCategory;
use App\Product\Domain\Repository\ProductCategoryQueryRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductCategory>
 */
class ProductCategoryQueryRepository extends ServiceEntityRepository implements ProductCategoryQueryRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductCategory::class);
    }

    /**
     * List product category ids.
     *
     * @return array<int>|null
     *
     * @throws Exception
     */
    public function listProductCategoryIds(int $productId): ?array
    {
        $sql = 'SELECT category_id
                FROM product_product_category
                WHERE product_id = :product_id
        ';

        $stmt = $this->getEntityManager()
            ->getConnection()
            ->prepare($sql);

        $stmt->bindValue('product_id', $productId, \PDO::PARAM_INT);

        return $stmt->executeQuery()->fetchFirstColumn();
    }
}
