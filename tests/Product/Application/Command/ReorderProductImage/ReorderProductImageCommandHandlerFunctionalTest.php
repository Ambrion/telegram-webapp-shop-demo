<?php

declare(strict_types=1);

namespace App\Tests\Product\Application\Command\ReorderProductImage;

use App\Product\Domain\Entity\Product;
use App\Product\Domain\Repository\ProductImageCommandRepositoryInterface;
use App\Product\Domain\Repository\ProductImageQueryRepositoryInterface;
use App\Tests\Resource\Fixture\Product\ProductImageFixture;
use App\Tests\Resource\Fixture\Product\ProductIsActiveFixture;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ReorderProductImageCommandHandlerFunctionalTest extends WebTestCase
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

    public function test_reorder_product_images_successfully(): void
    {
        $executor = $this->databaseTool
            ->loadFixtures([ProductIsActiveFixture::class, ProductImageFixture::class])
            ->getReferenceRepository();
        /** @var Product $product */
        $product = $executor->getReference(ProductIsActiveFixture::REFERENCE, Product::class);

        $findProductImage = $this->queryRepository->findProductImage($product->getId()->getValue());
        $this->assertNotEmpty($findProductImage, 'Cannot find product images.');
        $oldIds = array_keys($findProductImage);
        $newOrderIds = array_reverse(array_keys($findProductImage));

        $reorderResult = $this->commandRepository->reorder($product->getId()->getValue(), $newOrderIds);

        $this->assertEquals(count($newOrderIds), $reorderResult, 'Error image reorder: image count not equals.');
        $this->assertNotEquals($newOrderIds, $oldIds, 'Error image reorder: image array not equals.');
    }
}
