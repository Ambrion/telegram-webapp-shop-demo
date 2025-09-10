<?php

namespace App\Tests\Category\Application\Command\UpdateCategory;

use App\Category\Application\Command\UpdateCategory\UpdateCategoryCommand;
use App\Category\Application\Command\UpdateCategory\UpdateCategoryCommandHandler;
use App\Category\Domain\Entity\Category;
use App\Category\Domain\Factory\CategoryFactory;
use App\Category\Domain\Repository\CategoryCommandRepositoryInterface;
use App\Category\Domain\Repository\CategoryQueryRepositoryInterface;
use App\Category\Domain\ValueObject\IdValue;
use App\Shared\Application\Command\CommandBusInterface;
use App\Tests\Resource\Fixture\Category\CategoryIsActiveFixture;
use Faker\Factory;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\String\Slugger\SluggerInterface;

class UpdateCategoryCommandHandlerFunctionTest extends WebTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
        $this->commandBus = $this->getContainer()->get(CommandBusInterface::class);
        $this->categoryCommandRepository = $this->getContainer()->get(CategoryCommandRepositoryInterface::class);
        $this->categoryQueryRepository = $this->getContainer()->get(CategoryQueryRepositoryInterface::class);
        $this->slugger = $this->getContainer()->get(SluggerInterface::class);
        $this->databaseTool = $this->getContainer()->get(DatabaseToolCollection::class)->get();
    }

    public function test_category_update_successfully(): void
    {
        $executor = $this->databaseTool->loadFixtures([CategoryIsActiveFixture::class])->getReferenceRepository();
        /** @var Category $category */
        $category = $executor->getReference(CategoryIsActiveFixture::REFERENCE, Category::class);

        // Assert
        $this->assertInstanceOf(IdValue::class, $category->getId(), 'ID not instance of IdValue.');
        $this->assertIsInt($category->getId()->getValue(), 'ID category not integer.');
        $this->assertGreaterThan(0, $category->getId()->getValue(), 'ID category not greater then 0.');

        $categoryFactory = new CategoryFactory($this->slugger);
        $updateHandler = new UpdateCategoryCommandHandler(
            $this->categoryCommandRepository,
            $categoryFactory,
        );

        $newTitle = $this->faker->text(30).'update_'.time();
        $newParentId = 22;
        $newSortOrder = 33;
        $newSlug = $this->slugger->slug($newTitle)->lower()->toString();

        $updateCommand = new UpdateCategoryCommand(
            $category->getId()->getValue(),
            $newTitle,
            $newParentId,
            false,
            $newSortOrder,
        );

        // Act
        $result = ($updateHandler)($updateCommand);

        // Assert
        $this->assertEquals(1, $result, 'Error update category: result not equals 1.');

        $category = $this->categoryQueryRepository->findById($category->getId()->getValue());
        $this->assertNotEmpty($category, 'Cannot find category by ID.');

        $this->assertEquals($newTitle, $category['title'], 'Error update category: title not equals.');
        $this->assertEquals($newSlug, $category['slug'], 'Error update category: slut not equals.');
        $this->assertEquals($newParentId, $category['parent_id'], 'Error update category: parent_id not equals.');
        $this->assertEquals(false, $category['is_active'], 'Error update category: is_active not equals.');
        $this->assertEquals($newSortOrder, $category['sort_order'], 'Error update category: is_active not equals.');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->databaseTool);
    }
}
