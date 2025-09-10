<?php

declare(strict_types=1);

namespace App\Product\Domain\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FileUploaderInterface
{
    public function upload(UploadedFile $file, string $subDirectory = ''): string;
}
