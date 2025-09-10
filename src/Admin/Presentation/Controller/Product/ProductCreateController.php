<?php

declare(strict_types=1);

namespace App\Admin\Presentation\Controller\Product;

use App\Admin\Infrastructure\Adapter\Category\CategoryAdapterInterface;
use App\Admin\Infrastructure\Adapter\Category\CategoryFilterAdapterInterface;
use App\Admin\Infrastructure\Adapter\Product\ProductAdapterInterface;
use App\Admin\Infrastructure\Adapter\Product\ProductFormAdapterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/admin/product')]
class ProductCreateController extends AbstractController
{
    public function __construct(
        private readonly ProductAdapterInterface $productAdapter,
        private readonly ProductFormAdapterInterface $productFormAdapter,
        private readonly CategoryFilterAdapterInterface $categoryFilterAdapter,
        private readonly CategoryAdapterInterface $categoryAdapter,
        private readonly TranslatorInterface $translator,
    ) {
    }

    #[Route('/new', name: 'app_admin_product_new', methods: ['GET', 'POST'])]
    public function __invoke(Request $request): Response
    {
        $productDTO = $this->productAdapter->createEmptyProductDTO();

        $filter = $this->categoryFilterAdapter->createCategoryFilter();
        $filter->setIsActive(true);

        $categoryChoices = $this->categoryAdapter->findAllByCriteriaQuery($filter) ?? [];

        $form = $this->productFormAdapter->createForm($productDTO, $categoryChoices);

        $this->productFormAdapter->handleRequest($form, $request);

        if ($this->productFormAdapter->isSubmitted($form) && $this->productFormAdapter->isValid($form)) {
            $queryResult = $this->productAdapter->findProductByTitle($productDTO->title);

            if (!$queryResult) {
                if (!$this->isGranted('ROLE_ADMIN')) {
                    $message = $this->translator->trans(
                        'product.created.demo.successfully',
                        [
                            '%title%' => $productDTO->title,
                        ],
                        'admin.product.flash.messages',
                    );

                    $this->addFlash('success', $message);

                    return $this->redirectToRoute('app_admin_product_list', [], Response::HTTP_SEE_OTHER);
                }

                $productId = $this->productAdapter->createProduct($productDTO);

                if ($productId) {
                    $title = $productDTO->title;

                    $message = $this->translator->trans(
                        'product.created.successfully',
                        [
                            '%title%' => $title,
                            '%id%' => $productId,
                            '%url%' => $this->generateUrl('app_admin_product_edit', ['productId' => $productId]),
                        ],
                        'admin.product.flash.messages',
                    );

                    $this->addFlash('success', $message);

                    return $this->redirectToRoute('app_admin_product_list', [], Response::HTTP_SEE_OTHER);
                }
            } else {
                $errorMessage = $this->translator->trans(
                    'product.creation.error',
                    [],
                    'admin.product.flash.messages',
                );
                $this->addFlash('error', $errorMessage);
            }
        }

        return $this->render('admin/product/new.html.twig', [
            'form' => $form,
        ]);
    }
}
