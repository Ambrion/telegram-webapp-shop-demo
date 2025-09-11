<?php

declare(strict_types=1);

namespace App\Product\Application\UseCases\Command\DeleteProductImageFile;

use App\Shared\Application\Command\CommandHandlerInterface;

class DeleteProductImageFileCommandHandler implements CommandHandlerInterface
{
    public function __invoke(DeleteProductImageFileCommand $productCommand): int
    {
        $count = 0;

        foreach ($productCommand->imageFilePath as $filePath) {
            $fullPath = $productCommand->storagePath.'/'.$filePath;
            if (file_exists($fullPath) && is_file($fullPath)) {
                unlink($fullPath);

                ++$count;
            }
        }

        return $count;
    }
}
