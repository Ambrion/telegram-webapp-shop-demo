<?php

declare(strict_types=1);

namespace App\Tests\Product\Application\UseCases\Command\ProductCategory;

use App\Product\Domain\Entity\Product;
use App\Product\Domain\Repository\ProductImageQueryRepositoryInterface;
use App\Product\Domain\Repository\ProductQueryRepositoryInterface;
use App\Product\Domain\ValueObject\IdValue;
use App\Tests\Resource\Fixture\Product\ProductImageFixture;
use App\Tests\Resource\Fixture\Product\ProductIsActiveFixture;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateProductCommandHandlerFunctionalTest extends WebTestCase
{
    private ProductQueryRepositoryInterface $repository;
    private ProductImageQueryRepositoryInterface $imageRepository;
    protected AbstractDatabaseTool $databaseTool;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->getContainer()->get(ProductQueryRepositoryInterface::class);
        $this->imageRepository = $this->getContainer()->get(ProductImageQueryRepositoryInterface::class);
        $this->databaseTool = $this->getContainer()->get(DatabaseToolCollection::class)->get();
    }

    public function test_create_product_successfully(): void
    {
        $executor = $this->databaseTool
            ->loadFixtures([ProductIsActiveFixture::class, ProductImageFixture::class])
            ->getReferenceRepository();
        $product = $executor->getReference(ProductIsActiveFixture::REFERENCE, Product::class);

        // Assert
        $this->assertInstanceOf(Product::class, $product, 'Result not instance of Product.');
        $this->assertInstanceOf(IdValue::class, $product->getId(), 'ID not instance of IdValue.');
        $this->assertGreaterThan(0, $product->getId()->getValue(), 'Product ID not greater then 0.');
        $findProduct = $this->repository->findById($product->getId()->getValue());
        $this->assertNotEmpty($findProduct, 'Cannot find product by ID.');
        $findProductImage = $this->imageRepository->findProductImage($product->getId()->getValue());
        $this->assertNotEmpty($findProductImage, 'Cannot find product images.');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->databaseTool);
    }
}
