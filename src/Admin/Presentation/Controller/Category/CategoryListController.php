<?php

declare(strict_types=1);

namespace App\Admin\Presentation\Controller\Category;

use App\Admin\Infrastructure\Adapter\Category\CategoryAdapterInterface;
use App\Admin\Infrastructure\Adapter\Category\CategoryFilterAdapterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/category')]
class CategoryListController extends AbstractController
{
    public function __construct(
        private readonly ParameterBagInterface $parameterBag,
        private readonly CategoryAdapterInterface $categoryAdapter,
        private readonly CategoryFilterAdapterInterface $categoryFilterAdapter,
    ) {
    }

    #[Route('/list', name: 'app_admin_category_list', methods: ['GET'])]
    public function __invoke(Request $request): Response
    {
        $filter = $this->categoryFilterAdapter->createCategoryFilter();
        $form = $this->categoryFilterAdapter->createFilterForm($filter);
        $this->categoryFilterAdapter->handleRequest($form, $request);

        $limit = (int) ($this->parameterBag->get('item_per_page') ?? 10);
        // For sliding window pagination
        $maxVisiblePages = (int) ($this->parameterBag->get('max_visible_pages') ?? 5);
        $page = $request->query->getInt('page', 1);

        $offset = ($page - 1) * $limit;

        $totalItems = $this->categoryAdapter->countAllCategoryByFilter($filter);
        $list = $this->categoryAdapter->listCategoryWithPagination($filter, $offset, $limit);
        $totalPages = ceil($totalItems / $limit);

        $startPage = max(1, $page - floor($maxVisiblePages / 2));
        $endPage = min($totalPages, $startPage + $maxVisiblePages - 1);

        // Adjust start page if needed
        $startPage = max(1, $endPage - $maxVisiblePages + 1);

        return $this->render('admin/category/list.html.twig', [
            'list' => $list,
            'searchForm' => $form->createView(),
            'totalItems' => $totalItems,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'startPage' => $startPage,
            'endPage' => $endPage,
        ]);
    }
}
