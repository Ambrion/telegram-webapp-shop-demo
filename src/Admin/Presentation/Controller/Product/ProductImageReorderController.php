<?php

declare(strict_types=1);

namespace App\Admin\Presentation\Controller\Product;

use App\Admin\Infrastructure\Adapter\Product\ProductAdapterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/admin/product')]
class ProductImageReorderController extends AbstractController
{
    public function __construct(
        private readonly ProductAdapterInterface $productAdapter,
        private readonly TranslatorInterface $translator,
    ) {
    }

    #[Route('/{productId}/image/reorder', name: 'app_admin_product_image_reorder', methods: ['PUT'])]
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

        $imageIds = $data['sortOrder'] ?? [];

        if (!empty($imageIds)) {
            $result = $this->productAdapter->reorderProductImage($productId, $imageIds);

            if ($result) {
                $result = true;
            }
        }

        return $this->json([
            'result' => $result,
        ]);
    }
}
