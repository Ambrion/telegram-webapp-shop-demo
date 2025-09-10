<?php

declare(strict_types=1);

namespace App\Tests\Product\Application\Command\DeleteProductImageFile;

use App\Product\Application\Command\DeleteProductImageFile\DeleteProductImageFileCommand;
use App\Product\Application\Command\DeleteProductImageFile\DeleteProductImageFileCommandHandler;
use PHPUnit\Framework\TestCase;

class DeleteProductImageFileCommandHandlerFunctionalTest extends TestCase
{
    private DeleteProductImageFileCommandHandler $handler;
    private string $testStoragePath;
    private string $testFilePath;

    protected function setUp(): void
    {
        $this->handler = new DeleteProductImageFileCommandHandler();
        $this->testStoragePath = 'storage/test_images';

        // Create test directory if it doesn't exist
        if (!is_dir($this->testStoragePath)) {
            mkdir($this->testStoragePath, 0777, true);
        }

        $this->testFilePath = 'test_image.jpg';
    }

    protected function tearDown(): void
    {
        // Clean up test files
        $fullPath = $this->testStoragePath.'/'.$this->testFilePath;
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }

        // Remove test directory
        if (is_dir($this->testStoragePath)) {
            rmdir($this->testStoragePath);
        }
    }

    public function test_delete_existing_file(): void
    {
        // Arrange
        $fullPath = $this->testStoragePath.'/'.$this->testFilePath;

        file_put_contents($fullPath, 'test content');

        $command = new DeleteProductImageFileCommand(
            [$this->testFilePath],
            $this->testStoragePath
        );

        // Act
        $result = ($this->handler)($command);

        // Assert
        $this->assertEquals(1, $result);
        $this->assertFileDoesNotExist($fullPath);
    }

    public function test_delete_non_existent_file(): void
    {
        // Arrange
        $command = new DeleteProductImageFileCommand(
            ['non_existent_file.jpg'],
            $this->testStoragePath
        );

        // Act
        $result = ($this->handler)($command);

        // Assert
        $this->assertEquals(0, $result);
    }

    public function test_delete_multiple_files(): void
    {
        // Arrange
        $file1 = 'image1.jpg';
        $file2 = 'image2.png';
        $file3 = 'non_existent.jpg';

        // Create only first two files
        file_put_contents($this->testStoragePath.'/'.$file1, 'content1');
        file_put_contents($this->testStoragePath.'/'.$file2, 'content2');

        $command = new DeleteProductImageFileCommand(
            [$file1, $file2, $file3],
            $this->testStoragePath
        );

        // Act
        $result = ($this->handler)($command);

        // Assert
        $this->assertEquals(2, $result);
        $this->assertFileDoesNotExist($this->testStoragePath.'/'.$file1);
        $this->assertFileDoesNotExist($this->testStoragePath.'/'.$file2);
    }
}
