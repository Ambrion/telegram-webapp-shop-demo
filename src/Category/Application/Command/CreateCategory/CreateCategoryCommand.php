<?php

declare(strict_types=1);

namespace App\Category\Application\Command\CreateCategory;

use App\Shared\Application\Command\CommandInterface;

readonly class CreateCategoryCommand implements CommandInterface
{
    public function __construct(
        public string $title,
        public ?int $parentId,
        public bool $isActive,
        public ?int $sortOrder = 0,
    ) {
    }
}
