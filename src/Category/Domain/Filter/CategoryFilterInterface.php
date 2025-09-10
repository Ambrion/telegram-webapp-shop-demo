<?php

declare(strict_types=1);

namespace App\Category\Domain\Filter;

interface CategoryFilterInterface
{
    public function getTitle(): ?string;

    /**
     * @return $this
     */
    public function setTitle(?string $title): static;

    /**
     * @return array<int>
     */
    public function getIds(): array;

    /**
     * @param array<int> $ids
     *
     * @return $this
     */
    public function setIds(array $ids): static;

    /**
     * @return array<int>
     */
    public function getExceptIds(): array;

    /**
     * @param array<int> $exceptIds
     *
     * @return $this
     */
    public function setExceptIds(array $exceptIds): static;

    public function getParentId(): ?int;

    /**
     * @return $this
     */
    public function setParentId(?int $parentId): static;

    public function getIsActive(): ?bool;

    /**
     * @return $this
     */
    public function setIsActive(?bool $isActive): static;
}
