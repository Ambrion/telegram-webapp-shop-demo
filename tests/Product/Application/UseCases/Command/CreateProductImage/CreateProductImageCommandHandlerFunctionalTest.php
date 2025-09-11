<?php

declare(strict_types=1);

namespace App\Tests\Product\Application\UseCases\Command\CreateProductImage;

use App\Product\Domain\Entity\Product;
use App\Product\Domain\Repository\ProductImageQueryRepositoryInterface;
use App\Tests\Resource\Fixture\Product\ProductImageFixture;
use App\Tests\Resource\Fixture\Product\ProductIsActiveFixture;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateProductImageCommandHandlerFunctionalTest extends WebTestCase
{
    private ProductImageQueryRepositoryInterface $repository;

    protected AbstractDatabaseTool $databaseTool;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->getContainer()->get(ProductImageQueryRepositoryInterface::class);
        $this->databaseTool = $this->getContainer()->get(DatabaseToolCollection::class)->get();
    }

    public function test_create_product_images_successfully(): void
    {
        $executor = $this->databaseTool
            ->loadFixtures([ProductIsActiveFixture::class, ProductImageFixture::class])
            ->getReferenceRepository();
        /** @var Product $product */
        $product = $executor->getReference(ProductIsActiveFixture::REFERENCE, Product::class);

        $findProductImage = $this->repository->findProductImage($product->getId()->getValue());
        $this->assertNotEmpty($findProductImage, 'Cannot find product images.');

        $expected = ['tests/Resource/images/enter.jpeg', 'tests/Resource/images/registration.jpeg'];
        foreach ($expected as $image) {
            $this->assertContains($image, $findProductImage, 'Cannot find images '.$image.' in product.');
        }
    }
}
