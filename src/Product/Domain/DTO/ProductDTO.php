<?php

declare(strict_types=1);

namespace App\Product\Domain\DTO;

use App\Product\Domain\Entity\Product;
use Symfony\Component\Validator\Constraints as Assert;

class ProductDTO
{
    public function __construct(
        #[Assert\Type('int')]
        public ?int $id,

        #[Assert\NotBlank]
        #[Assert\Type('string')]
        public ?string $title,

        #[Assert\Type('string')]
        public ?string $description,

        #[Assert\Type('string')]
        public ?string $slug,

        #[Assert\NotBlank]
        #[Assert\Type('int')]
        public ?int $price,

        #[Assert\Type('bool')]
        public bool $isActive,

        /**
         * @var array<string|mixed>
         */
        public array $categories = [],

        /**
         * @var array<string|mixed>
         */
        public array $images = [],
    ) {
    }

    public function setId(?int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public static function fromEntity(Product $product): self
    {
        return new self(
            id: $product->getId()->getValue(),
            title: $product->getTitle()->getValue(),
            description: $product->getDescription()->getValue(),
            slug: $product->getSlug()->getValue(),
            price: $product->getPrice()->getValue(),
            isActive: $product->isActive()->getValue(),
            categories: $product->getCategories()->getElements() ?: [],
            images: $product->getImages()->getElements() ?: []
        );
    }
}
