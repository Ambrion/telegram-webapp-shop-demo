<?php

declare(strict_types=1);

namespace App\Tests\Resource\Fixture\Product;

use App\Product\Application\Command\CreateProductImage\CreateProductImageCommand;
use App\Product\Application\Command\CreateProductImage\CreateProductImageCommandHandler;
use App\Product\Domain\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProductImageFixture extends Fixture implements DependentFixtureInterface
{
    private string $imageDirectory;

    public function __construct(
        private readonly CreateProductImageCommandHandler $commandHandler,
        string $testFixtureImageDir,
    ) {
        $this->imageDirectory = $testFixtureImageDir;
    }

    public function load(ObjectManager $manager): void
    {
        /** @var Product $product */
        $product = $this->getReference(ProductIsActiveFixture::REFERENCE, Product::class);

        $images = $this->getTestImages();

        $command = new CreateProductImageCommand(
            $product->getId()->getValue(),
            $images
        );

        $this->commandHandler->__invoke($command);
    }

    private function getTestImages(): array
    {
        $images = [];
        $imageFiles = glob($this->imageDirectory.'/*.{jpg,jpeg,png,gif}', GLOB_BRACE);

        foreach ($imageFiles as $imageFile) {
            $uploadedFile = new UploadedFile(
                $imageFile,
                basename($imageFile),
                mime_content_type($imageFile),
                null,
                true // test mode
            );

            $images[] = $uploadedFile;
        }

        return $images;
    }

    public function getDependencies(): array
    {
        return [
            ProductIsActiveFixture::class,
        ];
    }
}
