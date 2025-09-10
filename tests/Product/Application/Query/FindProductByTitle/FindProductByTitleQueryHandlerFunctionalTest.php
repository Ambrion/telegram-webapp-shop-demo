<?php

declare(strict_types=1);

namespace App\Tests\Product\Application\Query\FindProductByTitle;

use App\Product\Application\Query\FindProductByTitle\FindProductByTitleQuery;
use App\Product\Application\Query\FindProductByTitle\FindProductByTitleQueryHandler;
use App\Product\Domain\DTO\ProductDTO;
use App\Product\Domain\Entity\Product;
use App\Product\Domain\Repository\ProductCategoryQueryRepositoryInterface;
use App\Product\Domain\Repository\ProductImageQueryRepositoryInterface;
use App\Product\Domain\Repository\ProductQueryRepositoryInterface;
use App\Tests\Resource\Fixture\Product\ProductImageFixture;
use App\Tests\Resource\Fixture\Product\ProductIsActiveFixture;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FindProductByTitleQueryHandlerFunctionalTest extends WebTestCase
{
    private ProductQueryRepositoryInterface $productQueryRepository;
    private ProductCategoryQueryRepositoryInterface $productCategoryQueryRepository;
    private ProductImageQueryRepositoryInterface $productImageQueryRepository;

    protected AbstractDatabaseTool $databaseTool;

    protected function setUp(): void
    {
        parent::setUp();
        $this->productQueryRepository = $this->getContainer()->get(ProductQueryRepositoryInterface::class);
        $this->productCategoryQueryRepository = $this->getContainer()->get(ProductCategoryQueryRepositoryInterface::class);
        $this->productImageQueryRepository = $this->getContainer()->get(ProductImageQueryRepositoryInterface::class);
        $this->databaseTool = $this->getContainer()->get(DatabaseToolCollection::class)->get();
    }

    public function test_rind_product_by_id_returns_product_dto(): void
    {
        $executor = $this->databaseTool
            ->loadFixtures([ProductIsActiveFixture::class, ProductImageFixture::class])
            ->getReferenceRepository();
        /** @var Product $product */
        $product = $executor->getReference(ProductIsActiveFixture::REFERENCE, Product::class);

        $queryHandler = new FindProductByTitleQueryHandler(
            $this->productQueryRepository,
            $this->productCategoryQueryRepository,
            $this->productImageQueryRepository
        );

        $query = new FindProductByTitleQuery($product->getTitle()->getValue());
        $productDTO = ($queryHandler)($query);

        // Assert
        $this->assertInstanceOf(ProductDTO::class, $productDTO, 'Error query: product not instance of ProductDTO.');
        $this->assertIsInt($productDTO->id, 'Error query: product ID is not an integer.');
        $this->assertNotEmpty($productDTO->id, 'Error query: product ID is empty.');
        $this->assertIsString($productDTO->title, 'Error query: product title is not a string.');
        $this->assertNotEmpty($productDTO->title, 'Error query: product title is empty.');
        $this->assertIsString($productDTO->description, 'Error query: product description is not a string.');
        $this->assertNotEmpty($productDTO->description, 'Error query: product description is empty.');
        $this->assertIsString($productDTO->slug, 'Error query: product slug is not a string.');
        $this->assertNotEmpty($productDTO->slug, 'Error query: product slug is empty.');
        $this->assertIsInt($productDTO->price, 'Error query: product price is not an integer.');
        $this->assertNotEmpty($productDTO->price, 'Error query: product price is empty.');
        $this->assertIsBool($productDTO->isActive, 'Error query: product isActive is not a boolean.');
        $this->assertNotEmpty($productDTO->categories, 'Error query: product categories is empty.');
        $this->assertNotEmpty($productDTO->images, 'Error query: product images is empty.');
    }

    public function test_product_return_null_when_not_find_by_id(): void
    {
        $queryHandler = new FindProductByTitleQueryHandler(
            $this->productQueryRepository,
            $this->productCategoryQueryRepository,
            $this->productImageQueryRepository
        );

        $query = new FindProductByTitleQuery('Not exists title');
        $result = ($queryHandler)($query);

        $this->assertNull($result);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->databaseTool);
    }
}
