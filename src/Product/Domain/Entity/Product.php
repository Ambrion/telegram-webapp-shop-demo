<?php

declare(strict_types=1);

namespace App\Product\Domain\Entity;

use App\Product\Domain\ValueObject\ActiveValue;
use App\Product\Domain\ValueObject\CategoriesValue;
use App\Product\Domain\ValueObject\DescriptionValue;
use App\Product\Domain\ValueObject\IdValue;
use App\Product\Domain\ValueObject\ImagesValue;
use App\Product\Domain\ValueObject\PriceValue;
use App\Product\Domain\ValueObject\SlugValue;
use App\Product\Domain\ValueObject\TitleValue;

class Product
{
    private ?IdValue $id = null;
    private ?TitleValue $title = null;
    private ?DescriptionValue $description = null;
    private ?SlugValue $slug = null;
    private ?PriceValue $price = null;
    private ?ActiveValue $isActive = null;
    private ?CategoriesValue $categories = null;
    private ?ImagesValue $images = null;

    public function getId(): ?IdValue
    {
        return $this->id;
    }

    public function setId(?IdValue $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getTitle(): ?TitleValue
    {
        return $this->title;
    }

    public function setTitle(TitleValue $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?DescriptionValue
    {
        return $this->description;
    }

    public function setDescription(DescriptionValue $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getSlug(): ?SlugValue
    {
        return $this->slug;
    }

    public function setSlug(SlugValue $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getPrice(): ?PriceValue
    {
        return $this->price;
    }

    public function setPrice(PriceValue $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function isActive(): ?ActiveValue
    {
        return $this->isActive;
    }

    public function setIsActive(ActiveValue $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getCategories(): ?CategoriesValue
    {
        return $this->categories;
    }

    public function setCategories(CategoriesValue $categories): static
    {
        $this->categories = $categories;

        return $this;
    }

    public function getImages(): ?ImagesValue
    {
        return $this->images;
    }

    public function setImages(ImagesValue $images): static
    {
        $this->images = $images;

        return $this;
    }
}
