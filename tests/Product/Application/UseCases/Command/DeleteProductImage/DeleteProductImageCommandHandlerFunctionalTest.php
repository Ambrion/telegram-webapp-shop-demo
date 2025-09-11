<?php

declare(strict_types=1);

namespace App\Tests\Product\Application\UseCases\Command\DeleteProductImage;

use App\Product\Domain\Entity\Product;
use App\Product\Domain\Repository\ProductImageCommandRepositoryInterface;
use App\Product\Domain\Repository\ProductImageQueryRepositoryInterface;
use App\Tests\Resource\Fixture\Product\ProductImageFixture;
use App\Tests\Resource\Fixture\Product\ProductIsActiveFixture;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DeleteProductImageCommandHandlerFunctionalTest extends WebTestCase
{
    private ProductImageCommandRepositoryInterface $commandRepository;
    private ProductImageQueryRepositoryInterface $queryRepository;

    protected AbstractDatabaseTool $databaseTool;

    public function setUp(): void
    {
        parent::setUp();
        $this->commandRepository = $this->getContainer()->get(ProductImageCommandRepositoryInterface::class);
        $this->queryRepository = $this->getContainer()->get(ProductImageQueryRepositoryInterface::class);
        $this->databaseTool = $this->getContainer()->get(DatabaseToolCollection::class)->get();
    }

    public function test_delete_product_images_successfully(): void
    {
        $executor = $this->databaseTool
            ->loadFixtures([ProductIsActiveFixture::class, ProductImageFixture::class])
            ->getReferenceRepository();
        /** @var Product $product */
        $product = $executor->getReference(ProductIsActiveFixture::REFERENCE, Product::class);

        $findProductImage = $this->queryRepository->findProductImage($product->getId()->getValue());
        $this->assertNotEmpty($findProductImage, 'Cannot find product images.');

        $deletedImages = $this->commandRepository->delete($product->getId()->getValue(), array_keys($findProductImage));

        $this->assertEquals(count($findProductImage), $deletedImages, 'Error image delete: image not delete.');
    }
}
