<?php

declare(strict_types=1);

namespace App\Tests\Resource\Fixture\Category;

use App\Category\Application\Command\CreateCategory\CreateCategoryCommand;
use App\Category\Application\Command\CreateCategory\CreateCategoryCommandHandler;
use App\Category\Domain\Factory\CategoryFactory;
use App\Category\Domain\Repository\CategoryCommandRepositoryInterface;
use App\Category\Domain\ValueObject\IdValue;
use App\Tests\Tools\FakerTools;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryIsActiveFixture extends Fixture
{
    use FakerTools;

    public const string REFERENCE = 'category';

    private CategoryFactory $categoryFactory;

    private CreateCategoryCommandHandler $handler;

    public function __construct(
        private readonly CategoryCommandRepositoryInterface $categoryCommandRepository,
        private readonly SluggerInterface $slugger,
    ) {
        $this->categoryFactory = new CategoryFactory($slugger);
        $this->handler = new CreateCategoryCommandHandler(
            $this->categoryCommandRepository,
            $this->categoryFactory,
        );
    }

    public function load(ObjectManager $manager): void
    {
        $category = $this->categoryFactory->create(
            id: null,
            title: $this->getFaker()->text(25),
            isActive: true,
            parentId: $this->getFaker()->randomDigitNotNull(),
            sortOrder: $this->getFaker()->randomDigitNotNull()
        );

        $command = new CreateCategoryCommand(
            title: $category->getTitle()->getValue(),
            parentId: $category->getParentId()->getValue(),
            isActive: $category->isActive()->getValue(),
            sortOrder: $category->getSortOrder()->getValue(),
        );

        $categoryId = ($this->handler)($command);

        $idValue = new IdValue($categoryId);
        $category->setId($idValue);

        $this->addReference(self::REFERENCE, $category);
    }
}
