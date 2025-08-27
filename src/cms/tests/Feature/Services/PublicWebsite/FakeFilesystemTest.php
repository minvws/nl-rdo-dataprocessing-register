<?php

declare(strict_types=1);

use App\Models\Organisation;
use App\Services\PublicWebsite\FakeFilesystem;
use Psr\Log\LoggerInterface;

it('can call deleteAll', function (): void {
    $logger = $this->mock(LoggerInterface::class)
        ->shouldReceive('debug')
        ->once()
        ->getMock();

    $contentGenerator = new FakeFilesystem($logger);
    $contentGenerator->deleteAll();
});

it('can call deleteDirectory', function (): void {
    $logger = $this->mock(LoggerInterface::class)
        ->shouldReceive('debug')
        ->once()
        ->getMock();

    $contentGenerator = new FakeFilesystem($logger);
    $contentGenerator->deleteDirectory('path');
});

it('can call deleteFile', function (): void {
    $logger = $this->mock(LoggerInterface::class)
        ->shouldReceive('debug')
        ->once()
        ->getMock();

    $contentGenerator = new FakeFilesystem($logger);
    $contentGenerator->deleteFile('path');
});

it('can call write', function (): void {
    $logger = $this->mock(LoggerInterface::class)
        ->shouldReceive('debug')
        ->once()
        ->getMock();

    $contentGenerator = new FakeFilesystem($logger);
    $contentGenerator->write('path', 'contents');
});

it('can call writeStream', function (): void {
    $organisation = Organisation::factory()
        ->withPosterImage()
        ->create();

    $logger = $this->mock(LoggerInterface::class)
        ->shouldReceive('debug')
        ->once()
        ->getMock();

    $contentGenerator = new FakeFilesystem($logger);
    $contentGenerator->writeStream('path', $organisation->getFilamentPoster()->stream());
});
