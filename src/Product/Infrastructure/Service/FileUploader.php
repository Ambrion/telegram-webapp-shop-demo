<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\Service;

use App\Product\Domain\Service\FileUploaderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader implements FileUploaderInterface
{
    public function __construct(
        public string $privateStorageDirectory,
        public SluggerInterface $slugger,
    ) {
    }

    public function upload(UploadedFile $file, string $subDirectory = ''): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        // Create directory if it doesn't exist
        $targetDirectory = $this->privateStorageDirectory.'/'.$subDirectory;
        if (!is_dir($targetDirectory)) {
            mkdir($targetDirectory, 0755, true);
        }

        // Move file to private storage
        $file->move($targetDirectory, $fileName);

        // Return relative path for storage in database
        return $subDirectory ? $subDirectory.'/'.$fileName : $fileName;
    }
}
