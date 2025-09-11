<?php

declare(strict_types=1);

namespace App\Tests\Product\Application\Command\UpdateProduct;

use App\Product\Application\UseCases\Command\UpdateProduct\UpdateProductCommand;
use App\Product\Application\UseCases\Command\UpdateProduct\UpdateProductCommandHandler;
use App\Product\Domain\Entity\Product;
use App\Product\Domain\Factory\ProductFactory;
use App\Product\Domain\Repository\ProductCommandRepositoryInterface;
use App\Product\Domain\Repository\ProductQueryRepositoryInterface;
use App\Product\Domain\Service\HandleProductImageUploadInterface;
use App\Product\Domain\ValueObject\IdValue;
use App\Tests\Resource\Fixture\Product\ProductIsActiveFixture;
use Faker\Factory;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\String\Slugger\SluggerInterface;

class UpdateProductCommandHandlerFunctionalTest extends WebTestCase
{
    protected AbstractDatabaseTool $databaseTool;
    private ProductQueryRepositoryInterface $productQueryRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
        $this->productCommandRepository = $this->getContainer()->get(ProductCommandRepositoryInterface::class);
        $this->productQueryRepository = $this->getContainer()->get(ProductQueryRepositoryInterface::class);
        $this->slugger = $this->getContainer()->get(SluggerInterface::class);
        $this->handleImageUpload = $this->getContainer()->get(HandleProductImageUploadInterface::class);
        $this->databaseTool = $this->getContainer()->get(DatabaseToolCollection::class)->get();
    }

    public function test_update_product_successfully(): void
    {
        $executor = $this->databaseTool
            ->loadFixtures([ProductIsActiveFixture::class])
            ->getReferenceRepository();
        /** @var Product $product */
        $product = $executor->getReference(ProductIsActiveFixture::REFERENCE, Product::class);

        // Assert
        $this->assertInstanceOf(Product::class, $product, 'Result not instance of Product.');
        $this->assertInstanceOf(IdValue::class, $product->getId(), 'ID not instance of IdValue.');
        $this->assertGreaterThan(0, $product->getId()->getValue(), 'Product ID not greater then 0.');

        $productFactory = new ProductFactory($this->slugger);
        $updateHandler = new UpdateProductCommandHandler(
            $this->productCommandRepository,
            $productFactory,
            $this->handleImageUpload
        );

        $newTitle = $this->faker->text(30).'update_'.time();
        $newDescription = $this->faker->text(100);
        $newPrice = $this->faker->randomDigitNotNull();

        $updateCommand = new UpdateProductCommand(
            id: $product->getId()->getValue(),
            title: $newTitle,
            description: $newDescription,
            price: $newPrice,
            isActive: true,
            categories: [$this->faker->randomDigitNotNull()],
            images: []
        );

        // Act
        $result = ($updateHandler)($updateCommand);

        // Assert
        $this->assertEquals(1, $result, 'Error update product: result not equals 1.');

        $newProduct = $this->productQueryRepository->findById($product->getId()->getValue());
        $this->assertNotEmpty($newProduct, 'Cannot find product by ID.');

        $this->assertEquals($newTitle, $newProduct['title'], 'Error update product: title not equals.');
        $this->assertEquals($newDescription, $newProduct['description'], 'Error update product: description not equals.');
        $this->assertEquals($newPrice, $newProduct['price'], 'Error update product: price not equals.');
    }
}
