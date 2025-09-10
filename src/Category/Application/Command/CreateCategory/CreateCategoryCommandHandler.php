<?php

declare(strict_types=1);

namespace App\Category\Application\Command\CreateCategory;

use App\Category\Domain\Factory\CategoryFactory;
use App\Category\Domain\Repository\CategoryCommandRepositoryInterface;
use App\Shared\Application\Command\CommandHandlerInterface;

readonly class CreateCategoryCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private CategoryCommandRepositoryInterface $categoryRepository,
        private CategoryFactory $categoryFactory,
    ) {
    }

    public function __invoke(CreateCategoryCommand $categoryCommand): int
    {
        $category = $this->categoryFactory->create(
            null,
            $categoryCommand->title,
            $categoryCommand->isActive,
            $categoryCommand->parentId,
            $categoryCommand->sortOrder
        );

        return $this->categoryRepository->add($category);
    }
}
