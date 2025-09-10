<?php

declare(strict_types=1);

namespace App\Tests\Category\Application\Command\CreateCategory;

use App\Category\Domain\Entity\Category;
use App\Category\Domain\Repository\CategoryQueryRepositoryInterface;
use App\Category\Domain\ValueObject\IdValue;
use App\Tests\Resource\Fixture\Category\CategoryIsActiveFixture;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateCategoryCommandHandlerFunctionalTest extends WebTestCase
{
    private CategoryQueryRepositoryInterface $categoryQueryRepository;

    protected AbstractDatabaseTool $databaseTool;

    public function setUp(): void
    {
        parent::setUp();
        $this->categoryQueryRepository = $this->getContainer()->get(CategoryQueryRepositoryInterface::class);
        $this->databaseTool = $this->getContainer()->get(DatabaseToolCollection::class)->get();
    }

    public function test_category_create_successfully(): void
    {
        $executor = $this->databaseTool->loadFixtures([CategoryIsActiveFixture::class])->getReferenceRepository();
        /** @var Category $category */
        $category = $executor->getReference(CategoryIsActiveFixture::REFERENCE, Category::class);

        // Assert
        $this->assertInstanceOf(IdValue::class, $category->getId(), 'ID not instance of IdValue.');
        $this->assertGreaterThan(0, $category->getId()->getValue(), 'Category ID not greater then 0.');
        $findCategory = $this->categoryQueryRepository->findById($category->getId()->getValue());
        $this->assertNotEmpty($findCategory, 'Cannot find category by ID.');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->databaseTool);
    }
}
