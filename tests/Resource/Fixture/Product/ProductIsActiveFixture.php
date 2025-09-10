<?php

declare(strict_types=1);

namespace App\Tests\Resource\Fixture\Product;

use App\Category\Domain\Entity\Category;
use App\Product\Application\Command\CreateProduct\CreateProductCommand;
use App\Product\Application\Command\CreateProduct\CreateProductCommandHandler;
use App\Product\Domain\Factory\ProductFactory;
use App\Product\Domain\Repository\ProductCommandRepositoryInterface;
use App\Product\Domain\Service\HandleProductImageUploadInterface;
use App\Product\Domain\ValueObject\IdValue;
use App\Tests\Resource\Fixture\Category\CategoryIsActiveFixture;
use App\Tests\Tools\FakerTools;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductIsActiveFixture extends Fixture implements DependentFixtureInterface
{
    use FakerTools;

    public const string REFERENCE = 'product';

    private ProductFactory $productFactory;
    private CreateProductCommandHandler $handler;

    public function __construct(
        private readonly ProductCommandRepositoryInterface $productCommandRepository,
        private readonly SluggerInterface $slugger,
        private readonly HandleProductImageUploadInterface $handleImageUpload,
    ) {
        $this->productFactory = new ProductFactory($this->slugger);
        $this->handler = new CreateProductCommandHandler(
            $this->productCommandRepository,
            $this->productFactory,
            $this->handleImageUpload
        );
    }

    public function load(ObjectManager $manager): void
    {
        /** @var Category $category */
        $category = $this->getReference(CategoryIsActiveFixture::REFERENCE, Category::class);

        $product = $this->productFactory->create(
            id: null,
            title: $this->getFaker()->text(25),
            description: $this->getFaker()->text(100),
            price: $this->getFaker()->randomDigitNotNull(),
            isActive: true,
            categories: [$category->getId()->getValue()],
            images: []
        );

        $command = new CreateProductCommand(
            $product->getTitle()->getValue(),
            $product->getDescription()->getValue(),
            $product->getPrice()->getValue(),
            $product->isActive()->getValue(),
            $product->getCategories()->getElements(),
            $product->getImages()->getElements(),
        );

        $productId = ($this->handler)($command);

        $idValue = new IdValue($productId);
        $product->setId($idValue);

        $this->addReference(self::REFERENCE, $product);
    }

    public function getDependencies(): array
    {
        return [
            CategoryIsActiveFixture::class,
        ];
    }
}
