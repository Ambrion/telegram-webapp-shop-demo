<?php

declare(strict_types=1);

namespace App\Product\Application\UseCases\Query\FindCartProductByIds;

use App\Product\Domain\DTO\ProductForOrderDTO;
use App\Product\Domain\Repository\ProductQueryRepositoryInterface;
use App\Shared\Application\Query\QueryHandlerInterface;
use App\Shared\Domain\Validation\QueryValidationIdsInterface;

readonly class FindCartProductByIdsQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private ProductQueryRepositoryInterface $repository,
        private QueryValidationIdsInterface $queryValidationIds,
    ) {
    }

    /**
     * @return ProductForOrderDTO[]|null
     */
    public function __invoke(FindCartProductByIdsQuery $query): ?array
    {
        $this->queryValidationIds->validateArrayIds($query->ids);

        $result = $this->repository->findAllActiveForOrderById($query->ids);

        if (!$result) {
            return null;
        }

        $productDTOs = [];
        foreach ($result as $productData) {
            $productDTOs[] = ProductForOrderDTO::fromArray($productData);
        }

        return $productDTOs;
    }
}
