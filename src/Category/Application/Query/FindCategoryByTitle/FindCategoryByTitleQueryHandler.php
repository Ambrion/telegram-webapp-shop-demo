<?php

declare(strict_types=1);

namespace App\Category\Application\Query\FindCategoryByTitle;

use App\Category\Application\DTO\CategoryDTO;
use App\Category\Domain\Repository\CategoryQueryRepositoryInterface;
use App\Shared\Application\Query\QueryHandlerInterface;

readonly class FindCategoryByTitleQueryHandler implements QueryHandlerInterface
{
    public function __construct(private CategoryQueryRepositoryInterface $repository)
    {
    }

    public function __invoke(FindCategoryByTitleQuery $query): ?CategoryDTO
    {
        $result = $this->repository->findOneByTitle($query->title);

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
