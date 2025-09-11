<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\Service;

use App\Product\Domain\Service\FileUploaderInterface;
use App\Product\Domain\Service\HandleProductImageUploadInterface;
use App\Product\Domain\Service\ProductCommandServiceInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\FlashBagAwareSessionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

readonly class HandleProductImageUpload implements HandleProductImageUploadInterface
{
    public function __construct(
        private ProductCommandServiceInterface $productCommandService,
        private FileUploaderInterface $fileUploader,
        private TranslatorInterface $translator,
        private RequestStack $requestStack,
    ) {
    }

    /**
     * @param array<string, mixed> $images
     */
    public function handle(int $productId, array $images): void
    {
        if (empty($images)) {
            return;
        }

        $uploads = [];

        foreach ($images as $image) {
            if ($image instanceof UploadedFile) {
                $uploads[] = $this->fileUploader->upload($image, 'products/'.$productId);
            }
        }

        if ($uploads) {
            $insertedImages = $this->productCommandService->createProductImage($productId, $uploads);
            if ($insertedImages) {
                $message = $this->translator->trans(
                    'product.image.upload.successfully',
                    [
                        '%count%' => $insertedImages,
                    ],
                    'admin.product.flash.messages',
                );

                /** @var FlashBagAwareSessionInterface $session */
                $session = $this->requestStack->getSession();
                $session->getFlashBag()->add('success', $message);
            }
        }
    }
}
