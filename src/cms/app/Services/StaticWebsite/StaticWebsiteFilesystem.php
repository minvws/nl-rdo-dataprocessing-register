<?php

declare(strict_types=1);

namespace App\Services\StaticWebsite;

interface StaticWebsiteFilesystem
{
    public function deleteAll(): void;

    public function deleteDirectory(string $path): void;

    public function deleteFile(string $path): void;

    public function write(string $path, string $contents): void;

    /**
     * @param resource $resource
     */
    public function writeStream(string $path, $resource): void;
}
