<?php

declare(strict_types=1);

namespace App\Admin\Presentation\Controller\Product;

use App\Admin\Domain\Exception\ProductNotFoundException;
use App\Admin\Infrastructure\Adapter\Category\CategoryAdapterInterface;
use App\Admin\Infrastructure\Adapter\Category\CategoryFilterAdapterInterface;
use App\Admin\Infrastructure\Adapter\Product\ProductAdapterInterface;
use App\Admin\Infrastructure\Adapter\Product\ProductFormAdapterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/admin/product')]
class ProductEditController extends AbstractController
{
    public function __construct(
        private readonly ProductAdapterInterface $productAdapter,
        private readonly ProductFormAdapterInterface $productFormAdapter,
        private readonly CategoryFilterAdapterInterface $categoryFilterAdapter,
        private readonly CategoryAdapterInterface $categoryAdapter,
        private readonly TranslatorInterface $translator,
    ) {
    }

    #[Route('/{productId}/edit', name: 'app_admin_product_edit', methods: ['GET', 'POST'])]
    public function __invoke(Request $request, int $productId): Response
    {
        if ($productId <= 0) {
            $message = $this->translator->trans(
                'product.id.error',
                [],
                'admin.product.flash.messages',
            );

            throw new BadRequestHttpException($message);
        }

        $productDTO = $this->productAdapter->findProductById($productId);
        if (null === $productDTO) {
            throw new ProductNotFoundException($productId);
        }

        $filter = $this->categoryFilterAdapter->createCategoryFilter();
        $filter->setIsActive(true);

        $categoryChoices = $this->categoryAdapter->findAllByCriteriaQuery($filter) ?? [];

        $form = $this->productFormAdapter->createForm($productDTO, $categoryChoices);

        $this->productFormAdapter->handleRequest($form, $request);

        if ($this->productFormAdapter->isSubmitted($form) && $this->productFormAdapter->isValid($form)) {
            $queryResult = $this->productAdapter->findProductByTitle($productDTO->title);

            if (!$queryResult || ($queryResult->id === $productDTO->id)) {
                if (!$this->isGranted('ROLE_ADMIN')) {
                    return $this->redirectAfterSuccess($productDTO->title, $productId);
                }

                $result = $this->productAdapter->updateProduct($productDTO);

                if ($result) {
                    return $this->redirectAfterSuccess($productDTO->title, $productId);
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

        return $this->render('admin/product/edit.html.twig', [
            'form' => $form,
            'categories' => json_encode(array_map('strval', $productDTO->categories ?? [])),
            'images' => $productDTO->images,
            'productId' => $productDTO->id,
        ]);
    }

    private function redirectAfterSuccess(string $title, int $productId): Response
    {
        $message = $this->translator->trans(
            'product.updated.successfully',
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
}
