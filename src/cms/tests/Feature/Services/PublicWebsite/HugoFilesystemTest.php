<?php

declare(strict_types=1);

use App\Models\Organisation;
use App\Services\PublicWebsite\HugoFilesystem;
use Illuminate\Filesystem\FilesystemAdapter;
use Psr\Log\NullLogger;

it('can call deleteAll', function (): void {
    /** @var FilesystemAdapter $filesystem */
    $filesystem = $this->createMock(FilesystemAdapter::class);
    $filesystem->expects($this->once())
        ->method('path');
    $filesystem->expects($this->once())
        ->method('deleteDirectory');

    $contentGenerator = new HugoFilesystem($filesystem, 'path', new NullLogger());
    $contentGenerator->deleteAll();
});

it('can call deleteDirectory', function (): void {
    /** @var FilesystemAdapter $filesystem */
    $filesystem = $this->createMock(FilesystemAdapter::class);
    $filesystem->expects($this->once())
        ->method('deleteDirectory');

    $contentGenerator = new HugoFilesystem($filesystem, 'path', new NullLogger());
    $contentGenerator->deleteDirectory('path');
});

it('can call deleteFile', function (): void {
    /** @var FilesystemAdapter $filesystem */
    $filesystem = $this->createMock(FilesystemAdapter::class);
    $filesystem->expects($this->once())
        ->method('delete');

    $contentGenerator = new HugoFilesystem($filesystem, 'path', new NullLogger());
    $contentGenerator->deleteFile('path');
});

it('can call write', function (): void {
    /** @var FilesystemAdapter $filesystem */
    $filesystem = $this->createMock(FilesystemAdapter::class);
    $filesystem->expects($this->once())
        ->method('put');

    $contentGenerator = new HugoFilesystem($filesystem, 'path', new NullLogger());
    $contentGenerator->write('path', 'contents');
});

it('can call writeStream', function (): void {
    $organisation = Organisation::factory()
        ->withPosterImage()
        ->create();

    /** @var FilesystemAdapter $filesystem */
    $filesystem = $this->createMock(FilesystemAdapter::class);
    $filesystem->expects($this->once())
        ->method('writeStream');

    $contentGenerator = new HugoFilesystem($filesystem, 'path', new NullLogger());
    $contentGenerator->writeStream('path', $organisation->getFilamentPoster()->stream());
});
