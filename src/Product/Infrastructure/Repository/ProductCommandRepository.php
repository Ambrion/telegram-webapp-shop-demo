<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\Repository;

use App\Product\Domain\Entity\Product;
use App\Product\Domain\Repository\ProductCommandRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Statement;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductCommandRepository extends ServiceEntityRepository implements ProductCommandRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Add product.
     *
     * @throws Exception
     */
    public function add(Product $product): int
    {
        $connection = $this->getEntityManager()->getConnection();

        try {
            $connection->beginTransaction();

            $sql = 'INSERT INTO product_product (
                    title,
                    description,
                    slug,
                    price,
                    is_active
                ) VALUES (
                    :title,
                    :description,
                    :slug,
                    :price,
                    :isActive
                )';

            $stmt = $this->getPrepare($sql, $product);

            $stmt->executeStatement();

            $productId = (int) $connection->lastInsertId();

            $placeholders = [];
            $values = [];

            foreach ($product->getCategories()->getElements() as $categoryId) {
                $placeholders[] = '(:product_id, :category_id_'.$categoryId.')';
                $values['category_id_'.$categoryId] = (int) $categoryId;
            }

            $values['product_id'] = $productId;

            $sql = 'INSERT INTO product_product_category (
                        product_id,
                        category_id
                    ) VALUES '.implode(', ', $placeholders);

            $stmt = $connection->prepare($sql);

            foreach ($values as $key => $value) {
                $stmt->bindValue($key, (int) $value, \PDO::PARAM_INT);
            }

            $stmt->executeStatement();

            $connection->commit();

            return $productId;
        } catch (\Exception $e) {
            if ($connection->isTransactionActive()) {
                $connection->rollBack();
            }
            throw new \Exception('Product creation error', 0, $e);
        }
    }

    /**
     * Update product.
     *
     * @throws \Exception
     */
    public function update(Product $product): int
    {
        $connection = $this->getEntityManager()->getConnection();

        try {
            $connection->beginTransaction();

            $sql = 'UPDATE product_product
                    SET title = :title,
                        description = :description,
                        slug = :slug,
                        price = :price,
                        is_active = :isActive
                    WHERE id = :id';

            $stmt = $this->getPrepare($sql, $product);
            $stmt->bindValue('id', $product->getId()->getValue(), \PDO::PARAM_INT);

            $stmt->executeStatement();

            $sqlDelete = 'DELETE
                          FROM product_product_category
                          WHERE product_id = :product_id';

            $stmt = $connection->prepare($sqlDelete);
            $stmt->bindValue('product_id', $product->getId()->getValue(), \PDO::PARAM_INT);

            $stmt->executeStatement();

            $placeholders = [];
            $values = [];

            foreach ($product->getCategories()->getElements() as $categoryId) {
                $placeholders[] = '(:product_id, :category_id_'.$categoryId.')';
                $values['category_id_'.$categoryId] = (int) $categoryId;
            }

            $values['product_id'] = (int) $product->getId()->getValue();

            $sqlAdd = 'INSERT INTO product_product_category (
                            product_id,
                            category_id
                        ) VALUES '.implode(', ', $placeholders);

            $stmt = $connection->prepare($sqlAdd);

            foreach ($values as $key => $value) {
                $stmt->bindValue($key, (int) $value, \PDO::PARAM_INT);
            }

            $stmt->executeStatement();

            if ($connection->commit()) {
                return 1;
            }

            return 0;
        } catch (Exception $e) {
            throw new \Exception('Product update error', 0, $e);
        }
    }

    /**
     * Prepare product stmt.
     *
     * @throws Exception
     */
    public function getPrepare(string $sql, Product $product): Statement
    {
        $stmt = $this->getEntityManager()
            ->getConnection()
            ->prepare($sql);

        $stmt->bindValue('title', $product->getTitle()->getValue(), \PDO::PARAM_STR);
        $stmt->bindValue('description', $product->getDescription()->getValue(), \PDO::PARAM_STR);
        $stmt->bindValue('slug', $product->getSlug()->getValue(), \PDO::PARAM_STR);
        $stmt->bindValue('price', $product->getPrice()->getValue(), \PDO::PARAM_INT);
        $stmt->bindValue('isActive', $product->isActive()->getValue(), \PDO::PARAM_BOOL);

        return $stmt;
    }
}
