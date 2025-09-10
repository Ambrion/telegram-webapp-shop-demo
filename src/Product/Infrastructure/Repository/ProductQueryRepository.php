<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\Repository;

use App\Product\Domain\Entity\Product;
use App\Product\Domain\Filter\ProductFilterInterface;
use App\Product\Domain\Repository\ProductQueryRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductQueryRepository extends ServiceEntityRepository implements ProductQueryRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * List products with filter and pagination.
     *
     * @return array<array<string, mixed>>
     *
     * @throws Exception
     */
    public function listProductWithPagination(ProductFilterInterface $productFilter, int $offset, int $limit): array
    {
        $addWhere = '';

        if ($productFilter->getTitle()) {
            $addWhere .= " AND title LIKE CONCAT('%', :searchValue, '%')";
        }

        $sql = "SELECT p.id,
                       p.title,
                       p.description,
                       p.price,
                       p.is_active,
                       p.slug,
                       MIN(pi.file_path) as file_path
                FROM product_product p
                    LEFT JOIN product_product_image pi
                        ON p.id = pi.product_id
                            AND pi.sort_order = 0
                WHERE 1
                $addWhere
                GROUP BY p.id
                LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->getEntityManager()
            ->getConnection()
            ->prepare($sql);

        if (!empty($productFilter->getTitle())) {
            $stmt->bindValue('searchValue', $productFilter->getTitle(), \PDO::PARAM_STR);
        }

        $stmt->bindValue('limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue('offset', $offset, \PDO::PARAM_INT);

        return $stmt->executeQuery()->fetchAllAssociative();
    }

    /**
     * Count all product.
     *
     * @throws Exception
     */
    public function countAll(ProductFilterInterface $productFilter): int
    {
        $addWhere = '';

        if ($productFilter->getTitle()) {
            $addWhere .= " AND title LIKE CONCAT('%', :searchValue, '%')";
        }

        $sql = "SELECT COUNT(*) AS total
                FROM product_product
                WHERE 1
                $addWhere
                ";

        $stmt = $this->getEntityManager()
            ->getConnection()
            ->prepare($sql);

        if (!empty($productFilter->getTitle())) {
            $stmt->bindValue('searchValue', $productFilter->getTitle(), \PDO::PARAM_STR);
        }

        return (int) $stmt->executeQuery()->fetchOne();
    }

    /**
     * Find by id.
     *
     * @return array<string, mixed>
     *
     * @throws Exception
     */
    public function findById(int $id): array
    {
        $sql = 'SELECT id,
                       title,
                       description,
                       slug,
                       price,
                       is_active
                FROM product_product
                WHERE id = :id
                ';

        $stmt = $this->getEntityManager()
            ->getConnection()
            ->prepare($sql);

        $stmt->bindValue('id', $id, \PDO::PARAM_INT);

        $result = $stmt->executeQuery()->fetchAssociative();
        if (false === $result) {
            return [];
        }

        return $result;
    }

    /**
     * Find one by title.
     *
     * @return array<string, mixed>
     *
     * @throws Exception
     */
    public function findOneByTitle(string $title): array
    {
        $sql = 'SELECT id,
                       title,
                       description,
                       slug,
                       price,
                       is_active
                FROM product_product
                WHERE title = :title
                LIMIT 1
                ';

        $stmt = $this->getEntityManager()
            ->getConnection()
            ->prepare($sql);

        $stmt->bindValue('title', $title, \PDO::PARAM_STR);

        $result = $stmt->executeQuery()->fetchAssociative();
        if (false === $result) {
            return [];
        }

        return $result;
    }

    /**
     * Find all active products by ids.
     *
     * @param array<int> $ids
     *
     * @return array<array<string, mixed>>
     *
     * @throws Exception
     */
    public function findAllActiveForOrderById(array $ids): array
    {
        $placeholders = array_map(function ($key) {
            return ':id_'.$key;
        }, array_keys($ids));

        $sql = 'SELECT id,
                       title,
                       price
                FROM product_product
                WHERE id IN ('.implode(', ', $placeholders).')
                    AND is_active = 1
                ';

        $stmt = $this->getEntityManager()
            ->getConnection()
            ->prepare($sql);

        foreach ($ids as $key => $id) {
            $stmt->bindValue(':id_'.$key, (int) $id, \PDO::PARAM_INT);
        }

        return $stmt->executeQuery()->fetchAllAssociative();
    }

    /**
     * Find all product with category.
     *
     * @return array<array<string, mixed>>
     *
     * @throws Exception
     */
    public function findAllProductCategory(): array
    {
        $sql = 'SELECT pp.id,
                       pp.title AS productTitle,
                       pp.description,
                       pp.price,
                       pp.slug,
                       pp.is_active,
                       cc.title AS categoryTitle
                FROM product_product_category AS ppc
                    LEFT JOIN product_product AS pp
                        ON pp.id = ppc.product_id
                    LEFT JOIN category_category AS cc
                        ON cc.id = ppc.category_id
                WHERE pp.is_active = 1
                    AND cc.is_active = 1
                ORDER BY cc.sort_order
        ';

        $stmt = $this->getEntityManager()
            ->getConnection()
            ->prepare($sql);

        return $stmt->executeQuery()->fetchAllAssociative();
    }
}
