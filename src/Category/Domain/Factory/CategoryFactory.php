<?php

declare(strict_types=1);

namespace App\Category\Domain\Factory;

use App\Category\Domain\Entity\Category;
use App\Category\Domain\ValueObject\ActiveValue;
use App\Category\Domain\ValueObject\IdValue;
use App\Category\Domain\ValueObject\ParentIdValue;
use App\Category\Domain\ValueObject\SlugValue;
use App\Category\Domain\ValueObject\SortOrderValue;
use App\Category\Domain\ValueObject\TitleValue;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryFactory
{
    public function __construct(public SluggerInterface $slugger)
    {
    }

    public function create(
        ?int $id,
        string $title,
        bool $isActive,
        ?int $parentId,
        ?int $sortOrder,
    ): Category {
        $category = new Category();

        $id = new IdValue($id);
        $category->setId($id);

        $titleValue = new TitleValue($title);
        $category->setTitle($titleValue);

        $slug = $this->slugger->slug($title)->lower()->toString();
        $slugValue = new SlugValue($slug);
        $category->setSlug($slugValue);

        $parentIdValue = new ParentIdValue($parentId);
        $category->setParentId($parentIdValue);

        $isActiveValue = new ActiveValue($isActive);
        $category->setIsActive($isActiveValue);

        $sortOrderValue = new SortOrderValue($sortOrder);
        $category->setSortOrder($sortOrderValue);

        return $category;
    }
}
