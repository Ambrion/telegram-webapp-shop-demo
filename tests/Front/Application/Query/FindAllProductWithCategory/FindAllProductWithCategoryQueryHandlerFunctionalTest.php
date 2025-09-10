<?php

use App\Product\Application\Query\FindAllProductWithCategory\FindAllProductWithCategoryQuery;
use App\Product\Application\Query\FindAllProductWithCategory\FindAllProductWithCategoryQueryHandler;
use App\Product\Domain\DTO\ProductCategoryDTO;
use App\Product\Domain\DTO\ProductDTO;
use App\Product\Domain\Entity\Product;
use App\Product\Domain\Repository\ProductImageQueryRepositoryInterface;
use App\Product\Domain\Repository\ProductQueryRepositoryInterface;
use App\Shared\Application\Command\CommandBusInterface;
use App\Tests\Resource\Fixture\Product\ProductIsActiveFixture;
use Faker\Factory;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\String\Slugger\SluggerInterface;

class FindAllProductWithCategoryQueryHandlerFunctionalTest extends WebTestCase
{
    protected AbstractDatabaseTool $databaseTool;

    public function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
        $this->commandBus = $this->getContainer()->get(CommandBusInterface::class);
        $this->productQueryRepository = $this->getContainer()->get(ProductQueryRepositoryInterface::class);
        $this->productImageQueryRepository = $this->getContainer()->get(ProductImageQueryRepositoryInterface::class);
        $this->slugger = $this->getContainer()->get(SluggerInterface::class);
        $this->databaseTool = $this->getContainer()->get(DatabaseToolCollection::class)->get();
    }

    public function test_find_all_product_with_category_successfully(): void
    {
        $executor = $this->databaseTool->loadFixtures([ProductIsActiveFixture::class])->getReferenceRepository();
        /** @var Product $product */
        $product = $executor->getReference(ProductIsActiveFixture::REFERENCE, Product::class);

        // Assert
        $this->assertInstanceOf(Product::class, $product, 'Error query: product not instance of Product.');
        $this->assertIsInt($product->getId()->getValue(), 'ID product not integer');
        $this->assertGreaterThan(0, $product->getId()->getValue(), 'ID product not greater then 0.');

        $queryHandler = new FindAllProductWithCategoryQueryHandler(
            $this->productQueryRepository,
            $this->productImageQueryRepository
        );

        $query = new FindAllProductWithCategoryQuery();
        $result = ($queryHandler)($query);

        // Assert
        $this->assertInstanceOf(ProductCategoryDTO::class, $result, 'Error query: result not instance of ProductCategoryDTO.');
        $categories = $result->getCategoryTitles();
        $this->assertCount(1, $categories, 'Error query: there must be at least one category.');
        $this->assertIsString($categories[0], 'Error query: category title is not a string.');

        $products = $result->getProductsByCategory($categories[0]);

        // Assert
        $this->assertInstanceOf(ProductDTO::class, $products[0], 'Error query: result product not instance of ProductDTO.');
        $this->assertIsInt($products[0]->id, 'Error query: product ID is not an integer.');
        $this->assertNotEmpty($products[0]->id, 'Error query: product ID is empty.');
        $this->assertIsString($products[0]->title, 'Error query: product title is not a string.');
        $this->assertNotEmpty($products[0]->title, 'Error query: product title is empty.');
        $this->assertIsString($products[0]->slug, 'Error query: product slug is not a string.');
        $this->assertNotEmpty($products[0]->slug, 'Error query: product slug is empty.');
        $this->assertIsString($products[0]->description, 'Error query: product description is not a string.');
        $this->assertNotEmpty($products[0]->description, 'Error query: product description is empty.');
        $this->assertIsInt($products[0]->price, 'Error query: product price is not an integer.');
        $this->assertNotEmpty($products[0]->price, 'Error query: product price is empty.');
        $this->assertIsBool($products[0]->isActive, 'Error query: product isActive is not a boolean.');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->databaseTool);
    }
}
