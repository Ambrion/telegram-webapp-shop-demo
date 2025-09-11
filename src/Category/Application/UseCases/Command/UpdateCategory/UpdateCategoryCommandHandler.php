<?php

declare(strict_types=1);

namespace App\Category\Application\UseCases\Command\UpdateCategory;

use App\Category\Domain\Factory\CategoryFactory;
use App\Category\Domain\Repository\CategoryCommandRepositoryInterface;
use App\Shared\Application\Command\CommandHandlerInterface;

readonly class UpdateCategoryCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private CategoryCommandRepositoryInterface $categoryRepository,
        private CategoryFactory $categoryFactory,
    ) {
    }

    public function __invoke(UpdateCategoryCommand $updateCommand): int
    {
        $category = $this->categoryFactory->create(
            $updateCommand->id,
            $updateCommand->title,
            $updateCommand->isActive,
            $updateCommand->parentId,
            $updateCommand->sortOrder
        );

        return $this->categoryRepository->update($category);
    }
}
