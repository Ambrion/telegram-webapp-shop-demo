<?php

declare(strict_types=1);

namespace App\Admin\Presentation\Controller\Product;

use App\Admin\Infrastructure\Adapter\Product\ProductAdapterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/admin/product')]
class ProductImageDeleteController extends AbstractController
{
    public function __construct(
        private readonly ProductAdapterInterface $productAdapter,
        private readonly TranslatorInterface $translator,
    ) {
    }

    #[Route('/{productId}/image/delete', name: 'app_admin_product_image_delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN')]
    public function __invoke(Request $request, int $productId): JsonResponse
    {
        $result = false;

        if ($productId <= 0) {
            $message = $this->translator->trans(
                'product.id.error',
                [],
                'admin.product.flash.messages',
            );

            throw new BadRequestHttpException($message);
        }

        $content = $request->getContent();

        $data = json_decode($content, true);

        $ids = $data['ids'] ?? [];

        if (!empty($ids)) {
            $storagePath = $this->getParameter('storage_path');

            $imagesPath = $this->productAdapter->findProductImagesByIds($ids);

            $result = $this->productAdapter->deleteProductImage($productId, $ids);

            if ($result && $imagesPath) {
                $this->productAdapter->deleteProductImageFile($imagesPath, $storagePath);

                $result = true;
            }
        }

        return $this->json([
            'result' => $result,
        ]);
    }
}
