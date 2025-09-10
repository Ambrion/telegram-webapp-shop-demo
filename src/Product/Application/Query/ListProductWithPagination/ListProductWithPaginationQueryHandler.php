<?php

declare(strict_types=1);

namespace App\Product\Application\Query\ListProductWithPagination;

use App\Product\Domain\DTO\ProductListDTO;
use App\Product\Domain\Repository\ProductQueryRepositoryInterface;
use App\Product\Domain\Service\ProductStatus;
use App\Shared\Application\Query\QueryHandlerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

readonly class ListProductWithPaginationQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private ProductQueryRepositoryInterface $repository,
        private TranslatorInterface $translator,
    ) {
    }

    /**
     * @return array<ProductListDTO>|null
     */
    public function __invoke(ListProductWithPaginationQuery $query): ?array
    {
        $result = $this->repository->listProductWithPagination($query->filter, $query->offset, $query->limit);

        if (!$result) {
            return null;
        }

        $DTOs = [];
        foreach ($result as $item) {
            $item['product_status'] = $this->translator->trans(
                ProductStatus::PRODUCT_STATUS[$item['is_active']],
                [],
                'admin.product.status'
            );

            $DTOs[] = ProductListDTO::fromArray($item);
        }

        return $DTOs;
    }
}
