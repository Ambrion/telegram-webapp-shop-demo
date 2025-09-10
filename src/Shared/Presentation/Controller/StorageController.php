<?php

declare(strict_types=1);

namespace App\Shared\Presentation\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class StorageController extends AbstractController
{
    public function __construct(private readonly string $storagePath)
    {
    }

    #[Route('/storage/{path}', name: 'storage_file', requirements: ['path' => '.+'])]
    public function getFile(string $path): Response
    {
        $filePath = $this->storagePath.'/'.$path;

        if (!file_exists($filePath)) {
            throw new NotFoundHttpException('File not found');
        }

        return new BinaryFileResponse($filePath);
    }
}
