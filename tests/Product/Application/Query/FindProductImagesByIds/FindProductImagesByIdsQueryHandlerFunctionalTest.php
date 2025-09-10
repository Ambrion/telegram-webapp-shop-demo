<?php

declare(strict_types=1);

namespace App\Tests\Product\Application\Query\FindProductImagesByIds;

use App\Product\Application\Query\FindProductImagesByIds\FindProductImagesByIdsQuery;
use App\Product\Application\Query\FindProductImagesByIds\FindProductImagesByIdsQueryHandler;
use App\Product\Domain\Entity\Product;
use App\Product\Domain\Repository\ProductImageQueryRepositoryInterface;
use App\Shared\Domain\Validation\QueryValidationIdsInterface;
use App\Tests\Resource\Fixture\Product\ProductImageFixture;
use App\Tests\Resource\Fixture\Product\ProductIsActiveFixture;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FindProductImagesByIdsQueryHandlerFunctionalTest extends WebTestCase
{
    private ProductImageQueryRepositoryInterface $productImageQueryRepository;
    private QueryValidationIdsInterface $queryValidationIds;

    protected AbstractDatabaseTool $databaseTool;

    protected function setUp(): void
    {
        parent::setUp();
        $this->productImageQueryRepository = $this->getContainer()->get(ProductImageQueryRepositoryInterface::class);
        $this->queryValidationIds = $this->getContainer()->get(QueryValidationIdsInterface::class);
        $this->databaseTool = $this->getContainer()->get(DatabaseToolCollection::class)->get();
    }

    public function test_find_product_images_by_images_ids_returns_array_with_path(): void
    {
        $executor = $this->databaseTool
            ->loadFixtures([ProductIsActiveFixture::class, ProductImageFixture::class])
            ->getReferenceRepository();
        /** @var Product $product */
        $product = $executor->getReference(ProductIsActiveFixture::REFERENCE, Product::class);

        $productImages = $this->productImageQueryRepository->findProductImage($product->getId()->getValue());

        $queryHandler = new FindProductImagesByIdsQueryHandler(
            $this->productImageQueryRepository,
            $this->queryValidationIds
        );

        $query = new FindProductImagesByIdsQuery(array_keys($productImages));
        $images = ($queryHandler)($query);

        $this->assertNotEmpty($images, 'Error query: product images is empty.');
    }

    public function test_find_product_images_by_images_ids_returns_empty_array(): void
    {
        $notExistsImageIds = [999, 1000];

        $queryHandler = new FindProductImagesByIdsQueryHandler(
            $this->productImageQueryRepository,
            $this->queryValidationIds
        );

        $query = new FindProductImagesByIdsQuery($notExistsImageIds);
        $images = ($queryHandler)($query);

        $this->assertEmpty($images, 'Error query: product images is not empty.');
        $this->assertNull($images, 'Error query: product images is not null.');
    }
}
