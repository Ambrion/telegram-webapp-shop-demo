<?php

declare(strict_types=1);

namespace App\Category\Infrastructure\Repository;

use App\Category\Domain\Entity\Category;
use App\Category\Domain\Filter\CategoryFilterInterface;
use App\Category\Domain\Repository\CategoryQueryRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 */
class CategoryQueryRepository extends ServiceEntityRepository implements CategoryQueryRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * List categories with filter and pagination.
     *
     * @return array<array<string, mixed>>
     *
     * @throws Exception
     */
    public function listCategoryWithPagination(CategoryFilterInterface $categoryFilter, int $offset, int $limit): array
    {
        $addWhere = '';

        if ($categoryFilter->getTitle()) {
            $addWhere .= " AND c.title LIKE CONCAT('%', :searchValue, '%')";
        }

        $sql = "SELECT c.id,
                       c.title,
                       c.slug,
                       c.parent_id,
                       c.is_active,
                       c.sort_order,
                       p.title AS parent_title
                FROM category_category AS c
                LEFT JOIN category_category AS p
                    ON c.parent_id = p.id
                WHERE 1
                $addWhere
                ORDER BY c.sort_order, c.id DESC
                LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->getEntityManager()
            ->getConnection()
            ->prepare($sql);

        if (!empty($categoryFilter->getTitle())) {
            $stmt->bindValue('searchValue', $categoryFilter->getTitle(), \PDO::PARAM_STR);
        }

        $stmt->bindValue('limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue('offset', $offset, \PDO::PARAM_INT);

        return $stmt->executeQuery()->fetchAllAssociative();
    }

    /**
     * Count all category.
     *
     * @throws Exception
     */
    public function countAll(CategoryFilterInterface $categoryFilter): int
    {
        $addWhere = '';

        if ($categoryFilter->getTitle()) {
            $addWhere .= " AND title LIKE CONCAT('%', :searchValue, '%')";
        }

        $sql = "SELECT COUNT(*) AS total
                FROM category_category
                WHERE 1
                $addWhere
                ";

        $stmt = $this->getEntityManager()
            ->getConnection()
            ->prepare($sql);

        if (!empty($categoryFilter->getTitle())) {
            $stmt->bindValue('searchValue', $categoryFilter->getTitle(), \PDO::PARAM_STR);
        }

        return (int) $stmt->executeQuery()->fetchOne();
    }

    /**
     * Find category by id.
     *
     * @return array<string, mixed>
     *
     * @throws Exception
     */
    public function findById(int $id): array
    {
        $sql = 'SELECT id,
                       title,
                       parent_id,
                       is_active,
                       slug,
                       sort_order
                FROM category_category
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
                       parent_id,
                       is_active,
                       slug,
                       sort_order
                FROM category_category
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
     * Find all categories by some criteria.
     *
     * @return array<array<string, mixed>>
     *
     * @throws Exception
     */
    public function findAllByCriteria(CategoryFilterInterface $categoryFilter): array
    {
        $whereConditions = [];
        $parameters = [];

        // Handle ID filtering
        if (!empty($categoryFilter->getIds())) {
            $placeholders = [];
            foreach ($categoryFilter->getIds() as $key => $id) {
                $placeholder = 'id_'.$key;
                $placeholders[] = ':'.$placeholder;
                $parameters[$placeholder] = (int) $id;
            }
            $whereConditions[] = 'id IN ('.implode(', ', $placeholders).')';
        }

        // Handle except ID filtering
        if (!empty($categoryFilter->getExceptIds())) {
            $exceptPlaceholders = [];
            foreach ($categoryFilter->getExceptIds() as $key => $exceptId) {
                $exceptPlaceholder = 'except_id_'.$key;
                $exceptPlaceholders[] = ':'.$exceptPlaceholder;
                $parameters[$exceptPlaceholder] = (int) $exceptId;
            }
            $whereConditions[] = 'id NOT IN ('.implode(', ', $exceptPlaceholders).')';
        }

        // Handle parent_id filtering
        if (null !== $categoryFilter->getParentId()) {
            $whereConditions[] = 'parent_id = :parent_id';
            $parameters['parent_id'] = (int) $categoryFilter->getParentId();
        }

        // Handle is_active filtering
        if (null !== $categoryFilter->getIsActive()) {
            $whereConditions[] = 'is_active = :is_active';
            $parameters['is_active'] = (int) $categoryFilter->getIsActive();
        }

        $sql = 'SELECT id,
                       title,
                       parent_id,
                       is_active,
                       slug,
                       sort_order
                FROM category_category';

        if (!empty($whereConditions)) {
            $sql .= ' WHERE '.implode(' AND ', $whereConditions);
        }

        $stmt = $this->getEntityManager()
            ->getConnection()
            ->prepare($sql);

        foreach ($parameters as $key => $value) {
            $stmt->bindValue($key, $value, \PDO::PARAM_INT);
        }

        return $stmt->executeQuery()->fetchAllAssociative();
    }
}
