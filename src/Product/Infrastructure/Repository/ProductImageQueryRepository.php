<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\Repository;

use App\Product\Domain\Entity\ProductImage;
use App\Product\Domain\Repository\ProductImageQueryRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductImage>
 */
class ProductImageQueryRepository extends ServiceEntityRepository implements ProductImageQueryRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductImage::class);
    }

    /**
     * Find product images.
     *
     * @return array<string, mixed>|null
     *
     * @throws Exception
     */
    public function findProductImage(int $productId): ?array
    {
        $sql = 'SELECT id,
                       file_path
                FROM product_product_image
                WHERE product_id = :product_id
                ORDER BY sort_order
        ';

        $stmt = $this->getEntityManager()
            ->getConnection()
            ->prepare($sql);

        $stmt->bindValue('product_id', $productId, \PDO::PARAM_INT);

        return $stmt->executeQuery()->fetchAllKeyValue();
    }

    /**
     * List product file path by ids.
     *
     * @param array<int> $ids
     *
     * @return array<mixed>|null
     *
     * @throws Exception
     */
    public function findProductFilePathByIds(array $ids): ?array
    {
        $placeholders = array_map(function ($key) {
            return ':image_id_'.$key;
        }, array_keys($ids));

        $sql = 'SELECT file_path
                FROM product_product_image
                WHERE id IN ('.implode(', ', $placeholders).')
        ';

        $stmt = $this->getEntityManager()
            ->getConnection()
            ->prepare($sql);

        foreach ($ids as $key => $imageId) {
            $stmt->bindValue(':image_id_'.$key, (int) $imageId, \PDO::PARAM_INT);
        }

        return $stmt->executeQuery()->fetchFirstColumn();
    }

    /**
     * Find product images by product ids grouped by product_id.
     *
     * @param array<int> $ids
     *
     * @return array<string, mixed>|null
     *
     * @throws Exception
     */
    public function findProductImageByProductIds(array $ids): ?array
    {
        $placeholders = array_map(function ($key) {
            return ':product_id_'.$key;
        }, array_keys($ids));

        $sql = 'SELECT product_id,
                       file_path
                FROM product_product_image
                WHERE product_id IN ('.implode(', ', $placeholders).')
                ORDER BY sort_order
        ';

        $stmt = $this->getEntityManager()
            ->getConnection()
            ->prepare($sql);

        foreach ($ids as $key => $productId) {
            $stmt->bindValue(':product_id_'.$key, $productId, \PDO::PARAM_INT);
        }

        $result = $stmt->executeQuery()->fetchAllAssociative();
        $groupedResult = [];
        foreach ($result as $row) {
            $productId = $row['product_id'];
            if (!isset($groupedResult[$productId])) {
                $groupedResult[$productId] = [];
            }
            $groupedResult[$productId][] = $row['file_path'];
        }

        return $groupedResult;
    }
}
