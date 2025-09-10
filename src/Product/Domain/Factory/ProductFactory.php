<?php

declare(strict_types=1);

namespace App\Product\Domain\Factory;

use App\Product\Domain\Entity\Product;
use App\Product\Domain\ValueObject\ActiveValue;
use App\Product\Domain\ValueObject\CategoriesValue;
use App\Product\Domain\ValueObject\DescriptionValue;
use App\Product\Domain\ValueObject\IdValue;
use App\Product\Domain\ValueObject\ImagesValue;
use App\Product\Domain\ValueObject\PriceValue;
use App\Product\Domain\ValueObject\SlugValue;
use App\Product\Domain\ValueObject\TitleValue;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductFactory
{
    public function __construct(public SluggerInterface $slugger)
    {
    }

    /**
     * @param array<int>           $categories
     * @param array<string, mixed> $images
     */
    public function create(
        ?int $id,
        string $title,
        ?string $description,
        int $price,
        bool $isActive,
        array $categories,
        array $images,
    ): Product {
        $product = new Product();

        $idValue = new IdValue($id);
        $product->setId($idValue);

        $titleValue = new TitleValue($title);
        $product->setTitle($titleValue);

        $slug = $this->slugger->slug($title)->lower()->toString();
        $slugValue = new SlugValue($slug);
        $product->setSlug($slugValue);

        $descriptionValue = new DescriptionValue($description);
        $product->setDescription($descriptionValue);

        $priceValue = new PriceValue($price);
        $product->setPrice($priceValue);

        $isActiveValue = new ActiveValue($isActive);
        $product->setIsActive($isActiveValue);

        $categoriesValues = new CategoriesValue($categories);
        $product->setCategories($categoriesValues);

        $imagesValues = new ImagesValue($images);
        $product->setImages($imagesValues);

        return $product;
    }
}
