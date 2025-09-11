<?php

declare(strict_types=1);

namespace App\Category\Application\Service;

use App\Category\Application\UseCases\Query\CountAllCategoryByFilter\CountAllCategoryByFilterQuery;
use App\Category\Application\UseCases\Query\FindAllByCriteria\FindAllByCriteriaQuery;
use App\Category\Application\UseCases\Query\FindCategoryById\FindCategoryByIdQuery;
use App\Category\Application\UseCases\Query\FindCategoryByTitle\FindCategoryByTitleQuery;
use App\Category\Application\UseCases\Query\ListCategoryWithPagination\ListCategoryWithPaginationQuery;
use App\Category\Domain\DTO\CategoryDTO;
use App\Category\Domain\DTO\CategoryListDTO;
use App\Category\Domain\Filter\CategoryFilterInterface;
use App\Category\Domain\Service\CategoryQueryServiceInterface;
use App\Shared\Application\Query\QueryBusInterface;

readonly class CategoryQueryService implements CategoryQueryServiceInterface
{
    public function __construct(private QueryBusInterface $queryBus)
    {
    }

    /**
     * Find category by id.
     */
    public function findCategoryByIdQuery(int $id): ?CategoryDTO
    {
        $query = new FindCategoryByIdQuery($id);

        return $this->queryBus->execute($query);
    }

    /**
     * Find category by title.
     */
    public function findCategoryByTitleQuery(string $title): ?CategoryDTO
    {
        $query = new FindCategoryByTitleQuery($title);

        return $this->queryBus->execute($query);
    }

    /**
     * Find all category by criteria.
     *
     * @return array<CategoryDTO>|null
     */
    public function findAllByCriteriaQuery(CategoryFilterInterface $filter): ?array
    {
        $query = new FindAllByCriteriaQuery($filter);

        return $this->queryBus->execute($query);
    }

    /**
     * Count all category by filter.
     */
    public function countAllCategoryByFilter(CategoryFilterInterface $filter): int
    {
        $query = new CountAllCategoryByFilterQuery($filter);

        return $this->queryBus->execute($query);
    }

    /**
     * List categories with pagination.
     *
     * @return array<CategoryListDTO>|null
     */
    public function listCategoryWithPagination(CategoryFilterInterface $filter, int $offset, int $limit): ?array
    {
        $query = new ListCategoryWithPaginationQuery(
            filter: $filter,
            offset: $offset,
            limit: $limit
        );

        return $this->queryBus->execute($query);
    }
}
