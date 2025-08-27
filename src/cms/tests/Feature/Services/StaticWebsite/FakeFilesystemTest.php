<?php

declare(strict_types=1);

use App\Models\Organisation;
use App\Services\StaticWebsite\FakeFilesystem;
use Psr\Log\LoggerInterface;

it('can call deleteAll', function (): void {
    $logger = $this->mock(LoggerInterface::class)
        ->shouldReceive('debug')
        ->once()
        ->getMock();

    $fakeFilesystem = new FakeFilesystem($logger);
    $fakeFilesystem->deleteAll();
});

it('can call deleteDirectory', function (): void {
    $logger = $this->mock(LoggerInterface::class)
        ->shouldReceive('debug')
        ->once()
        ->getMock();

    $fakeFilesystem = new FakeFilesystem($logger);
    $fakeFilesystem->deleteDirectory('path');
});

it('can call deleteFile', function (): void {
    $logger = $this->mock(LoggerInterface::class)
        ->shouldReceive('debug')
        ->once()
        ->getMock();

    $fakeFilesystem = new FakeFilesystem($logger);
    $fakeFilesystem->deleteFile('path');
});

it('can call write', function (): void {
    $logger = $this->mock(LoggerInterface::class)
        ->shouldReceive('debug')
        ->once()
        ->getMock();

    $fakeFilesystem = new FakeFilesystem($logger);
    $fakeFilesystem->write('path', 'contents');
});

it('can call writeStream', function (): void {
    $organisation = Organisation::factory()
        ->withPosterImage()
        ->createQuietly();

    $logger = $this->mock(LoggerInterface::class)
        ->shouldReceive('debug')
        ->once()
        ->getMock();

    $fakeFilesystem = new FakeFilesystem($logger);
    $fakeFilesystem->writeStream('path', $organisation->getFilamentPoster()->stream());
});
