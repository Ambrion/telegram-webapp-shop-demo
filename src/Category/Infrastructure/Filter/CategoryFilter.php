<?php

declare(strict_types=1);

namespace App\Category\Infrastructure\Filter;

use App\Category\Domain\Filter\CategoryFilterInterface;

class CategoryFilter implements CategoryFilterInterface
{
    private ?string $title = null;
    /**
     * @var array<int>
     */
    private array $ids = [];
    /**
     * @var array<int>
     */
    private array $exceptIds = [];
    private ?int $parentId = null;
    private ?bool $isActive = null;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return int[]
     */
    public function getIds(): array
    {
        return $this->ids;
    }

    /**
     * @param array<int> $ids
     *
     * @return $this
     */
    public function setIds(array $ids): static
    {
        $this->ids = $ids;

        return $this;
    }

    /**
     * @return int[]
     */
    public function getExceptIds(): array
    {
        return $this->exceptIds;
    }

    /**
     * @param array<int> $exceptIds
     *
     * @return $this
     */
    public function setExceptIds(array $exceptIds): static
    {
        $this->exceptIds = $exceptIds;

        return $this;
    }

    public function getParentId(): ?int
    {
        return $this->parentId;
    }

    /**
     * @return $this
     */
    public function setParentId(?int $parentId): static
    {
        $this->parentId = $parentId;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    /**
     * @return $this
     */
    public function setIsActive(?bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }
}
