<?php

namespace App\Tests\Category\Application\UseCases\Query\FindCategoryById;

use App\Category\Application\UseCases\Query\FindCategoryById\FindCategoryByIdQuery;
use App\Category\Application\UseCases\Query\FindCategoryById\FindCategoryByIdQueryHandler;
use App\Category\Domain\DTO\CategoryDTO;
use App\Category\Domain\Entity\Category;
use App\Category\Domain\Repository\CategoryQueryRepositoryInterface;
use App\Tests\Resource\Fixture\Category\CategoryIsActiveFixture;
use Faker\Factory;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FindCategoryByIdQueryHandlerFunctionalTest extends WebTestCase
{
    protected AbstractDatabaseTool $databaseTool;

    public function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
        $this->categoryQueryRepository = $this->getContainer()->get(CategoryQueryRepositoryInterface::class);
        $this->databaseTool = $this->getContainer()->get(DatabaseToolCollection::class)->get();
    }

    public function test_category_find_by_id_successfully(): void
    {
        $executor = $this->databaseTool->loadFixtures([CategoryIsActiveFixture::class])->getReferenceRepository();
        /** @var Category $category */
        $category = $executor->getReference(CategoryIsActiveFixture::REFERENCE, Category::class);

        $queryHandler = new FindCategoryByIdQueryHandler($this->categoryQueryRepository);

        $query = new FindCategoryByIdQuery($category->getId()->getValue());
        $categoryDTO = ($queryHandler)($query);

        // Assert
        $this->assertInstanceOf(CategoryDTO::class, $categoryDTO, 'Error query: category not instance of CategoryDTO.');
        $this->assertIsInt($categoryDTO->id, 'Error query: category ID is not an integer.');
        $this->assertNotEmpty($categoryDTO->id, 'Error query: category ID is empty.');
        $this->assertIsString($categoryDTO->title, 'Error query: category title is not a string.');
        $this->assertNotEmpty($categoryDTO->title, 'Error query: category title is empty.');
        $this->assertIsString($categoryDTO->slug, 'Error query: category slug is not a string.');
        $this->assertNotEmpty($categoryDTO->slug, 'Error query: category slug is empty.');
        $this->assertIsInt($categoryDTO->parentId, 'Error query: category parentId is not an integer.');
        $this->assertIsBool($categoryDTO->isActive, 'Error query: category isActive is not a boolean.');
        $this->assertIsInt($categoryDTO->sortOrder, 'Error query: category sortOrder is not an integer.');
    }

    public function test_category_return_null_when_not_find_by_id(): void
    {
        $queryHandler = new FindCategoryByIdQueryHandler($this->categoryQueryRepository);

        $query = new FindCategoryByIdQuery(999);
        $result = ($queryHandler)($query);

        $this->assertNull($result);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->databaseTool);
    }
}
