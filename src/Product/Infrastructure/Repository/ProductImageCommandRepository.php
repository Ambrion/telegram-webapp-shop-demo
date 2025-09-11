<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\Repository;

use App\Product\Domain\Entity\ProductImage;
use App\Product\Domain\Repository\ProductImageCommandRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductImage>
 */
class ProductImageCommandRepository extends ServiceEntityRepository implements ProductImageCommandRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductImage::class);
    }

    /**
     * Add images.
     *
     * @param array<string> $images
     *
     * @throws Exception
     * @throws \Exception
     */
    public function add(int $productId, array $images): int
    {
        $connection = $this->getEntityManager()->getConnection();

        try {
            $connection->beginTransaction();

            $placeholders = [];
            $values = [];

            foreach ($images as $key => $image) {
                $placeholders[] = '(:product_id, :file_path_'.$key.', :sort_order_'.$key.')';
                $values['file_path_'.$key] = (string) $image;
                $values['sort_order_'.$key] = (int) $key;
            }

            $values['product_id'] = $productId;

            $sql = 'INSERT INTO product_product_image (
                        product_id,
                        file_path,
                        sort_order
                    ) VALUES '.implode(', ', $placeholders);

            $stmt = $connection->prepare($sql);

            foreach ($values as $key => $value) {
                $stmt->bindValue($key, (string) $value, \PDO::PARAM_STR);
            }

            $insertedRows = $stmt->executeStatement();

            if ($connection->commit()) {
                return $insertedRows;
            }

            return 0;
        } catch (\Exception $e) {
            if ($connection->isTransactionActive()) {
                $connection->rollBack();
            }
            throw new \Exception('Product image creation error', 0, $e);
        }
    }

    /**
     * Delete product image.
     *
     * @param array<int> $imageIds
     *
     * @throws Exception
     */
    public function delete(int $productId, array $imageIds): int
    {
        $connection = $this->getEntityManager()->getConnection();

        try {
            $connection->beginTransaction();

            $placeholders = array_map(function ($key) {
                return ':image_id_'.$key;
            }, array_keys($imageIds));

            $sql = 'DELETE
                    FROM product_product_image
                    WHERE product_id = :product_id
                    AND id IN ('.implode(', ', $placeholders).')
                    ';

            $stmt = $connection->prepare($sql);
            $stmt->bindValue('product_id', $productId, \PDO::PARAM_INT);

            foreach ($imageIds as $key => $imageId) {
                $stmt->bindValue(':image_id_'.$key, $imageId, \PDO::PARAM_INT);
            }

            $deletedRows = $stmt->executeStatement();

            if ($connection->commit()) {
                return $deletedRows;
            }

            return 0;
        } catch (\Exception $e) {
            if ($connection->isTransactionActive()) {
                $connection->rollBack();
            }
            throw new \Exception('Product image delete error', 0, $e);
        }
    }

    /**
     * Reorder product images.
     *
     * @param array<int> $imageIds
     *
     * @throws Exception
     */
    public function reorder(int $productId, array $imageIds): int
    {
        $connection = $this->getEntityManager()->getConnection();

        try {
            $connection->beginTransaction();

            $caseConditions = [];
            $values = ['product_id' => $productId];

            foreach ($imageIds as $sort_order => $id) {
                $caseConditions[] = "WHEN :image_id_$sort_order THEN :sort_order_$sort_order";
                $values["image_id_$sort_order"] = (int) $id;
                $values["sort_order_$sort_order"] = (int) $sort_order;
            }

            $placeholders = [];
            foreach (array_keys($imageIds) as $index => $key) {
                $placeholders[] = ":in_image_id_$index";
                $values["in_image_id_$index"] = (int) $imageIds[$key];
            }

            $sql = 'UPDATE product_product_image
                    SET sort_order =
                        CASE
                            id '.implode(' ', $caseConditions).'
                        END
                    WHERE product_id = :product_id
                        AND id IN ('.implode(', ', $placeholders).')';

            $stmt = $connection->prepare($sql);
            foreach ($values as $key => $value) {
                $stmt->bindValue($key, $value, \PDO::PARAM_INT);
            }

            $updatedRows = $stmt->executeStatement();

            if ($connection->commit()) {
                return $updatedRows;
            }

            return 0;
        } catch (\Exception $e) {
            if ($connection->isTransactionActive()) {
                $connection->rollBack();
            }
            throw new \Exception('Product image reorder error', 0, $e);
        }
    }
}
