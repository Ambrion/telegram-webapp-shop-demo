<?php

declare(strict_types=1);

namespace App\Front\Presentation\Controller;

use App\Front\Infrastructure\Adapter\ProductAdapterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomePageController extends AbstractController
{
    public function __construct(private readonly ProductAdapterInterface $productAdapter)
    {
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $productCategoryDTO = $this->productAdapter->findAllProductWithCategory();

        return $this->render('shop/home/index.html.twig', [
            'categories' => $productCategoryDTO ?? [],
        ]);
    }
}
