<?php

declare(strict_types=1);

namespace App\Category\Infrastructure\Repository;

use App\Category\Domain\Entity\Category;
use App\Category\Domain\Repository\CategoryCommandRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Statement;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 */
class CategoryCommandRepository extends ServiceEntityRepository implements CategoryCommandRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * Add category.
     *
     * @throws Exception
     */
    public function add(Category $category): int
    {
        $connection = $this->getEntityManager()->getConnection();

        try {
            $connection->beginTransaction();

            $sql = 'INSERT INTO category_category (
                        title,
                        slug,
                        parent_id,
                        is_active,
                        sort_order
                    ) VALUES (
                        :title,
                        :slug,
                        :parentId,
                        :isActive,
                        :sortOrder
                    )';

            $stmt = $this->getPrepare($sql, $category);

            $stmt->executeStatement();

            $productId = (int) $connection->lastInsertId();

            $connection->commit();

            return $productId;
        } catch (Exception $e) {
            if ($connection->isTransactionActive()) {
                $connection->rollBack();
            }
            throw new Exception('Category creation error', 0, $e);
        }
    }

    /**
     * Update category.
     *
     * @throws Exception
     */
    public function update(Category $category): int
    {
        $connection = $this->getEntityManager()->getConnection();

        try {
            $connection->beginTransaction();

            $sql = 'UPDATE category_category
                    SET title = :title,
                        slug = :slug,
                        parent_id = :parentId,
                        is_active = :isActive,
                        sort_order = :sortOrder
                    WHERE id = :id';

            $stmt = $this->getPrepare($sql, $category);
            $stmt->bindValue('id', $category->getId()->getValue(), \PDO::PARAM_INT);

            $stmt->executeStatement();

            if ($connection->commit()) {
                return 1;
            }

            return 0;
        } catch (Exception $e) {
            if ($connection->isTransactionActive()) {
                $connection->rollBack();
            }
            throw new Exception('Category update error', 0, $e);
        }
    }

    /**
     * Prepare category stmt.
     *
     * @throws Exception
     */
    public function getPrepare(string $sql, Category $category): Statement
    {
        $stmt = $this->getEntityManager()
            ->getConnection()
            ->prepare($sql);

        $stmt->bindValue('title', $category->getTitle()->getValue(), \PDO::PARAM_STR);
        $stmt->bindValue('slug', $category->getSlug()->getValue(), \PDO::PARAM_STR);
        $stmt->bindValue('parentId', $category->getParentId()->getValue(), \PDO::PARAM_INT);
        $stmt->bindValue('isActive', $category->isActive()->getValue(), \PDO::PARAM_BOOL);
        $stmt->bindValue('sortOrder', $category->getSortOrder()->getValue(), \PDO::PARAM_INT);

        return $stmt;
    }
}
