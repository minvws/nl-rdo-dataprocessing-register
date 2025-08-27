<?php

declare(strict_types=1);

use App\Models\Organisation;
use App\Services\StaticWebsite\HugoFilesystem;
use Illuminate\Filesystem\FilesystemAdapter;
use Psr\Log\NullLogger;

it('can call deleteAll', function (): void {
    $filesystem = $this->mock(FilesystemAdapter::class)
        ->shouldReceive('path')
        ->once()
        ->shouldReceive('deleteDirectory')
        ->once()
        ->getMock();

    $contentGenerator = new HugoFilesystem($filesystem, 'path', new NullLogger());
    $contentGenerator->deleteAll();
});

it('can call deleteDirectory', function (): void {
    $filesystem = $this->mock(FilesystemAdapter::class)
        ->shouldReceive('deleteDirectory')
        ->once()
        ->getMock();

    $contentGenerator = new HugoFilesystem($filesystem, 'path', new NullLogger());
    $contentGenerator->deleteDirectory('path');
});

it('can call deleteFile', function (): void {
    $filesystem = $this->mock(FilesystemAdapter::class)
        ->shouldReceive('delete')
        ->once()
        ->getMock();

    $contentGenerator = new HugoFilesystem($filesystem, 'path', new NullLogger());
    $contentGenerator->deleteFile('path');
});

it('can call write', function (): void {
    $filesystem = $this->mock(FilesystemAdapter::class)
        ->shouldReceive('put')
        ->once()
        ->getMock();

    $contentGenerator = new HugoFilesystem($filesystem, 'path', new NullLogger());
    $contentGenerator->write('path', 'contents');
});

it('can call writeStream', function (): void {
    $organisation = Organisation::factory()
        ->withPosterImage()
        ->createQuietly();

    $filesystem = $this->mock(FilesystemAdapter::class)
        ->shouldReceive('writeStream')
        ->once()
        ->getMock();

    $contentGenerator = new HugoFilesystem($filesystem, 'path', new NullLogger());
    $contentGenerator->writeStream('path', $organisation->getFilamentPoster()->stream());
});
