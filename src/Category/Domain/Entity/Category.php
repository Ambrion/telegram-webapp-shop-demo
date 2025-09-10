<?php

declare(strict_types=1);

namespace App\Category\Domain\Entity;

use App\Category\Domain\ValueObject\ActiveValue;
use App\Category\Domain\ValueObject\IdValue;
use App\Category\Domain\ValueObject\ParentIdValue;
use App\Category\Domain\ValueObject\SlugValue;
use App\Category\Domain\ValueObject\SortOrderValue;
use App\Category\Domain\ValueObject\TitleValue;

class Category
{
    private ?IdValue $id = null;
    private ?TitleValue $title = null;
    private ?SlugValue $slug = null;
    private ?ParentIdValue $parentId = null;
    private ?ActiveValue $isActive = null;

    public ?SortOrderValue $sortOrder = null;

    public function getId(): ?IdValue
    {
        return $this->id;
    }

    /**
     * @return $this
     */
    public function setId(IdValue $id): static
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

    public function getSlug(): ?SlugValue
    {
        return $this->slug;
    }

    public function setSlug(SlugValue $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getParentId(): ?ParentIdValue
    {
        return $this->parentId;
    }

    public function setParentId(ParentIdValue $parentId): static
    {
        $this->parentId = $parentId;

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

    public function getSortOrder(): ?SortOrderValue
    {
        return $this->sortOrder;
    }

    public function setSortOrder(SortOrderValue $sortOrder): static
    {
        $this->sortOrder = $sortOrder;

        return $this;
    }
}
