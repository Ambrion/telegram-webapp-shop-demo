<?php

declare(strict_types=1);

namespace App\Category\Application\UseCases\Query\ListCategoryWithPagination;

use App\Category\Domain\DTO\CategoryListDTO;
use App\Category\Domain\Repository\CategoryQueryRepositoryInterface;
use App\Category\Domain\Service\CategoryStatus;
use App\Shared\Application\Query\QueryHandlerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

readonly class ListCategoryWithPaginationQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private CategoryQueryRepositoryInterface $repository,
        private TranslatorInterface $translator,
    ) {
    }

    /**
     * @return array<CategoryListDTO>|null
     */
    public function __invoke(ListCategoryWithPaginationQuery $query): ?array
    {
        $result = $this->repository->listCategoryWithPagination($query->filter, $query->offset, $query->limit);

        if (!$result) {
            return null;
        }

        $DTOs = [];
        foreach ($result as $item) {
            $item['category_status'] = $this->translator->trans(
                CategoryStatus::CATEGORY_STATUS[$item['is_active']],
                [],
                'admin.category.status'
            );

            $DTOs[] = CategoryListDTO::fromArray($item);
        }

        return $DTOs;
    }
}
