<?php

declare(strict_types=1);

namespace App\Services\StaticWebsite;

use Psr\Log\LoggerInterface;

class FakeFilesystem implements StaticWebsiteFilesystem
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

    public function deleteAll(): void
    {
        $this->logger->debug('Fake deleted content directory');
    }

    public function deleteDirectory(string $path): void
    {
        $this->logger->debug('Fake deleted directory', [
            'path' => $path,
        ]);
    }

    public function deleteFile(string $path): void
    {
        $this->logger->debug('Fake deleted file', [
            'path' => $path,
        ]);
    }

    public function write(string $path, string $contents): void
    {
        $this->logger->debug('Fake written content file', [
            'path' => $path,
            'contents' => $contents,
        ]);
    }

    /**
     * @param resource $resource
     */
    public function writeStream(string $path, $resource): void
    {
        $this->logger->debug('Fake written stream', [
            'path' => $path,
        ]);
    }
}
