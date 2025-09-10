<?php

declare(strict_types=1);

namespace App\Category\Application\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CategoryDTO
{
    public function __construct(
        #[Assert\Type('int')]
        public ?int $id,

        #[Assert\NotBlank]
        #[Assert\Type('string')]
        public ?string $title,

        #[Assert\Type('string')]
        public ?string $slug,

        #[Assert\Type('int')]
        public ?int $parentId,

        #[Assert\Type('bool')]
        public ?bool $isActive,

        #[Assert\Type('int')]
        public ?int $sortOrder = 0,
    ) {
    }

    public function setId(?int $id): static
    {
        $this->id = $id;

        return $this;
    }
}
