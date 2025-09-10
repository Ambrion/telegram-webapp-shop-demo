<?php

declare(strict_types=1);

namespace App\Tests\Front\Presentation\Controller;

use App\Product\Domain\Entity\Product;
use App\Tests\Resource\Fixture\Product\ProductImageFixture;
use App\Tests\Resource\Fixture\Product\ProductIsActiveFixture;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomePageControllerFunctionalTest extends WebTestCase
{
    private KernelBrowser $client;

    public function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->databaseTool = $this->getContainer()->get(DatabaseToolCollection::class)->get();
    }

    public function test_home_page_controller_return_categories_has_page_title_successfully(): void
    {
        $executor = $this->databaseTool
            ->loadFixtures([ProductIsActiveFixture::class, ProductImageFixture::class])
            ->getReferenceRepository();
        $executor->getReference(ProductIsActiveFixture::REFERENCE, Product::class);

        $crawler = $this->client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Товары дня');
        $this->assertCount(1, $crawler->filter('#products > .row > .col'), 'Error: there must be at least one category with one product.');
    }

    public function test_home_page_controller_return_empty_categories_has_page_title_successfully(): void
    {
        $this->databaseTool->loadFixtures();

        $this->client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h5', 'Идёт наполнение товарами. Зайдите, пожалуйста, позже.');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->databaseTool);
    }
}
