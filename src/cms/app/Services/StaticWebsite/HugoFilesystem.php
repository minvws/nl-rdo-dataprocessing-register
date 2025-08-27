<?php

declare(strict_types=1);

namespace App\Services\StaticWebsite;

use Illuminate\Contracts\Filesystem\Filesystem;
use Psr\Log\LoggerInterface;

use function sprintf;

class HugoFilesystem implements StaticWebsiteFilesystem
{
    public function __construct(
        private readonly Filesystem $filesystem,
        private readonly string $hugoContentFolder,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function deleteAll(): void
    {
        $directory = $this->hugoContentFolder;

        $this->logger->debug('Deleting directory...', [
            'directory' => $this->filesystem->path($directory),
        ]);

        $this->filesystem->deleteDirectory($directory);
    }

    public function deleteDirectory(string $path): void
    {
        $directory = sprintf('%s/%s', $this->hugoContentFolder, $path);

        $this->logger->debug('Deleting directory...', [
            'directory' => $directory,
        ]);

        $this->filesystem->deleteDirectory($directory);
    }

    public function deleteFile(string $path): void
    {
        $file = sprintf('%s/%s', $this->hugoContentFolder, $path);

        $this->logger->debug('Deleting file...', [
            'file' => $file,
        ]);

        $this->filesystem->delete($file);
    }

    public function write(string $path, string $contents): void
    {
        $this->logger->debug('Writing contents...', [
            'path' => $path,
        ]);

        $this->filesystem->put(sprintf('%s/%s', $this->hugoContentFolder, $path), $contents);
    }

    /**
     * @param resource $resource
     */
    public function writeStream(string $path, $resource): void
    {
        $this->filesystem->writeStream(sprintf('%s/%s', $this->hugoContentFolder, $path), $resource);
    }
}
