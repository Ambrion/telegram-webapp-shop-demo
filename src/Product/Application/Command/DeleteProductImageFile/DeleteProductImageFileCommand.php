<?php

declare(strict_types=1);

namespace App\Product\Application\Command\DeleteProductImageFile;

use App\Shared\Application\Command\CommandInterface;

class DeleteProductImageFileCommand implements CommandInterface
{
    /**
     * @param array<string> $imageFilePath
     */
    public function __construct(public array $imageFilePath, public string $storagePath)
    {
    }
}
