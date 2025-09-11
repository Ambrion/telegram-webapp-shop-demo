<?php

declare(strict_types=1);

namespace App\Product\Application\Service;

use App\Product\Application\UseCases\Query\CountAllProductByFilter\CountAllProductByFilterQuery;
use App\Product\Application\UseCases\Query\FindAllProductWithCategory\FindAllProductWithCategoryQuery;
use App\Product\Application\UseCases\Query\FindCartProductByIds\FindCartProductByIdsQuery;
use App\Product\Application\UseCases\Query\FindProductById\FindProductByIdQuery;
use App\Product\Application\UseCases\Query\FindProductByTitle\FindProductByTitleQuery;
use App\Product\Application\UseCases\Query\FindProductImagesByIds\FindProductImagesByIdsQuery;
use App\Product\Application\UseCases\Query\ListProductWithPagination\ListProductWithPaginationQuery;
use App\Product\Domain\Service\ProductQueryServiceInterface;
use App\Product\Domain\DTO\ProductCategoryDTO;
use App\Product\Domain\DTO\ProductDTO;
use App\Product\Domain\DTO\ProductForOrderDTO;
use App\Product\Domain\DTO\ProductListDTO;
use App\Product\Domain\Filter\ProductFilterInterface;
use App\Shared\Application\Query\QueryBusInterface;
use App\Shared\Domain\DTO\OrderProductDTO;

readonly class ProductQueryService implements ProductQueryServiceInterface
{
    public function __construct(
        private QueryBusInterface $queryBus,
    ) {
    }

    /**
     * Find cart products by ids.
     *
     * @param array<int> $ids
     *
     * @return ProductForOrderDTO[]|null
     */
    public function findCartProductByIds(array $ids): ?array
    {
        $query = new FindCartProductByIdsQuery($ids);

        return $this->queryBus->execute($query);
    }

    /**
     * Create products from cart.
     *
     * @param array<string, mixed> $cart
     *
     * @return OrderProductDTO[]|null
     */
    public function createProductFromCart(array $cart): ?array
    {
        $productIds = array_column($cart, 'id');

        $productForOrderDTO = $this->findCartProductByIds($productIds);

        if (empty($productForOrderDTO)) {
            return null;
        }

        $cartItems = [];
        foreach ($cart as $cartItem) {
            $cartItems[$cartItem['id']] = $cartItem['quantity'];
        }

        foreach ($productForOrderDTO as $product) {
            $productId = $product->id;
            if (isset($cartItems[$productId])) {
                $quantity = $cartItems[$productId];
                $totalPrice = $product->price * $quantity;

                $orderProduct[] = OrderProductDTO::fromArray([
                    'product_id' => $productId,
                    'title' => $product->title,
                    'quantity' => $quantity,
                    'price' => $product->price,
                    'total_price' => $totalPrice,
                ]);
            }
        }

        if (empty($orderProduct)) {
            return null;
        }

        return $orderProduct;
    }

    /**
     * Find all products with category.
     */
    public function findAllProductWithCategory(): ?ProductCategoryDTO
    {
        $query = new FindAllProductWithCategoryQuery();

        return $this->queryBus->execute($query);
    }

    /**
     * Find product by id.
     */
    public function findProductById(int $id): ?ProductDTO
    {
        $query = new FindProductByIdQuery($id);

        return $this->queryBus->execute($query);
    }

    /**
     * Find product by title.
     */
    public function findProductByTitle(string $title): ?ProductDTO
    {
        $query = new FindProductByTitleQuery($title);

        return $this->queryBus->execute($query);
    }

    /**
     * Find product images by ids.
     *
     * @param array<int> $ids
     *
     * @return array<mixed>|null
     */
    public function findProductImagesByIds(array $ids): ?array
    {
        $query = new FindProductImagesByIdsQuery($ids);

        return $this->queryBus->execute($query);
    }

    /**
     * Count all products by filter.
     */
    public function countAllProductByFilter(ProductFilterInterface $filter): int
    {
        $query = new CountAllProductByFilterQuery($filter);

        return $this->queryBus->execute($query);
    }

    /**
     * List products with pagination.
     *
     * @return array<ProductListDTO>|null
     */
    public function listProductWithPagination(ProductFilterInterface $filter, int $offset, int $limit): ?array
    {
        $query = new ListProductWithPaginationQuery($filter, $offset, $limit);

        return $this->queryBus->execute($query);
    }
}
