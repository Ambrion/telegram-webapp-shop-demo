<?php

declare(strict_types=1);

namespace App\Category\Application\UseCases\Query\FindAllByCriteria;

use App\Category\Domain\DTO\CategoryDTO;
use App\Category\Domain\Repository\CategoryQueryRepositoryInterface;
use App\Shared\Application\Query\QueryHandlerInterface;
use App\Shared\Domain\Validation\QueryValidationIdsInterface;

readonly class FindAllByCriteriaQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private CategoryQueryRepositoryInterface $repository,
        private QueryValidationIdsInterface $queryValidationIds,
    ) {
    }

    /**
     * @return array<CategoryDTO>|null Array of CategoryDTO objects or null if no results
     */
    public function __invoke(FindAllByCriteriaQuery $query): ?array
    {
        if (!empty($query->filter->getIds())) {
            $this->queryValidationIds->validateArrayIds($query->filter->getIds(), 'All IDs must be positive integers and greater then zero');
        }

        if (!empty($query->filter->getExceptIds())) {
            $this->queryValidationIds->validateArrayIds($query->filter->getExceptIds(), 'All except IDs must be positive integers and greater then zero');
        }

        if (!is_null($query->filter->getParentId())) {
            $this->queryValidationIds->validateArrayIds([$query->filter->getParentId()], 'parentId must be positive integers and greater then zero');
        }

        $result = $this->repository->findAllByCriteria($query->filter);

        if (!$result) {
            return null;
        }

        $DTOs = [];
        foreach ($result as $item) {
            $DTOs[] = new CategoryDTO(
                $item['id'],
                $item['title'],
                $item['slug'],
                $item['parent_id'],
                (bool) $item['is_active'],
                $item['sort_order']
            );
        }

        return $DTOs;
    }
}
