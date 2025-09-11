<?php

declare(strict_types=1);

namespace App\Category\Application\UseCases\Query\FindCategoryById;

use App\Category\Domain\DTO\CategoryDTO;
use App\Category\Domain\Repository\CategoryQueryRepositoryInterface;
use App\Shared\Application\Query\QueryHandlerInterface;

readonly class FindCategoryByIdQueryHandler implements QueryHandlerInterface
{
    public function __construct(private CategoryQueryRepositoryInterface $repository)
    {
    }

    public function __invoke(FindCategoryByIdQuery $query): ?CategoryDTO
    {
        $result = $this->repository->findById($query->id);

        if (!$result) {
            return null;
        }

        return new CategoryDTO(
            $result['id'],
            $result['title'],
            $result['slug'],
            $result['parent_id'],
            (bool) $result['is_active'],
            $result['sort_order']
        );
    }
}
