<?php

declare(strict_types=1);

namespace App\Admin\Presentation\Controller\Order;

use App\Admin\Infrastructure\Adapter\Order\OrderAdapterInterface;
use App\Admin\Infrastructure\Adapter\Order\OrderFilterAdapterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/order')]
class OrderListController extends AbstractController
{
    public function __construct(
        private readonly ParameterBagInterface $parameterBag,
        private readonly OrderAdapterInterface $orderAdapter,
        private readonly OrderFilterAdapterInterface $orderFilterAdapter,
    ) {
    }

    #[Route('/list', name: 'app_admin_order_list', methods: ['GET'])]
    public function __invoke(Request $request): Response
    {
        $filter = $this->orderFilterAdapter->createOrderFilter();
        $form = $this->orderFilterAdapter->createFilterForm($filter);
        $this->orderFilterAdapter->handleRequest($form, $request);

        $limit = (int) ($this->parameterBag->get('item_per_page') ?? 10);
        // For sliding window pagination
        $maxVisiblePages = (int) ($this->parameterBag->get('max_visible_pages') ?? 5);
        $page = $request->query->getInt('page', 1);

        $offset = ($page - 1) * $limit;

        $totalItems = $this->orderAdapter->countAllOrderByFilter($filter);
        $list = $this->orderAdapter->listOrderWithPagination($filter, $offset, $limit);
        $totalPages = ceil($totalItems / $limit);

        $startPage = max(1, $page - floor($maxVisiblePages / 2));
        $endPage = min($totalPages, $startPage + $maxVisiblePages - 1);

        // Adjust start page if needed
        $startPage = max(1, $endPage - $maxVisiblePages + 1);

        return $this->render('admin/order/list.html.twig', [
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
