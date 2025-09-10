<?php

declare(strict_types=1);

namespace App\Users\Infrastructure\Repository;

use App\Users\Domain\Entity\User;
use App\Users\Domain\Repository\UserQueryRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserQueryRepository extends ServiceEntityRepository implements UserQueryRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Find user by ulid.
     *
     * @return array<string, mixed>|false
     *
     * @throws Exception
     */
    public function findByUlid(string $ulid): array|false
    {
        return $this->fetchUserByField('ulid', $ulid, \PDO::PARAM_STR);
    }

    /**
     * Find user by email.
     *
     * @return array<string, mixed>|false
     *
     * @throws Exception
     */
    public function findByEmail(string $email): array|false
    {
        return $this->fetchUserByField('email', $email, \PDO::PARAM_STR);
    }

    /**
     * Find user by telegramId.
     *
     * @return array<string, mixed>|false
     *
     * @throws Exception
     */
    public function findUserByTelegramId(int $telegramId): array|false
    {
        return $this->fetchUserByField('telegram_id', $telegramId, \PDO::PARAM_INT);
    }

    /**
     * Fetch user by a specific field.
     *
     * @return array<string, mixed>|false
     *
     * @throws Exception
     */
    private function fetchUserByField(string $field, mixed $value, int $paramType): array|false
    {
        $sql = "SELECT ulid,
                       email,
                       roles,
                       telegram_id,
                       telegram_username
                FROM users_user
                WHERE $field = :value
                ";

        try {
            $stmt = $this->getEntityManager()
                ->getConnection()
                ->prepare($sql);

            $stmt->bindValue('value', $value, $paramType);

            return $stmt->executeQuery()->fetchAssociative();
        } catch (\Exception $e) {
            throw new Exception("Database error while fetching user by $field: ".$e->getMessage(), 0, $e);
        }
    }
}
