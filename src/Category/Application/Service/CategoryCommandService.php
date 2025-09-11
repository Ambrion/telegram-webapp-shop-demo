<?php

declare(strict_types=1);

namespace App\Category\Application\Service;

use App\Category\Application\UseCases\Command\CreateCategory\CreateCategoryCommand;
use App\Category\Application\UseCases\Command\UpdateCategory\UpdateCategoryCommand;
use App\Category\Domain\DTO\CategoryDTO;
use App\Category\Domain\Service\CategoryCommandServiceInterface;
use App\Shared\Application\Command\CommandBusInterface;

readonly class CategoryCommandService implements CategoryCommandServiceInterface
{
    public function __construct(private CommandBusInterface $commandBus)
    {
    }

    /**
     * Create category.
     *
     * @return int id category
     */
    public function createCategory(string $title, ?int $parentId, bool $isActive, ?int $sortOrder = 0): int
    {
        $command = new CreateCategoryCommand($title, $parentId, $isActive);

        return $this->commandBus->execute($command);
    }

    /**
     * Update category.
     */
    public function updateCategory(int $id, string $title, ?int $parentId, bool $isActive, ?int $sortOrder): int
    {
        $command = new UpdateCategoryCommand($id, $title, $parentId, $isActive, $sortOrder);

        return $this->commandBus->execute($command);
    }

    /**
     * Create empty category DTO.
     */
    public function createEmptyCategoryDTO(): CategoryDTO
    {
        return new CategoryDTO(null, null, null, null, false, 0);
    }
}
