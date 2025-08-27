<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Media;

use App\Actions\Media\MediaContentHasher;
use App\Vendor\MediaLibrary\Media;
use Illuminate\Support\Facades\Storage;
use Webmozart\Assert\InvalidArgumentException;

use function fake;
use function it;

it('generates a content hash', function (): void {
    $path = fake()->sentence;

    $media = $this->mock(Media::class)
        ->shouldReceive('getPathRelativeToRoot')
        ->times(2)
        ->andReturn($path)
        ->getMock();

    Storage::shouldReceive('exists')->andReturn(true);
    Storage::shouldReceive('disk')->andReturnSelf();
    Storage::shouldReceive('get')->with($path)->andReturn('lorem ipsum dolor sit amet');

    $mediaContentHasher = $this->app->get(MediaContentHasher::class);
    $hash = $mediaContentHasher->hash($media);

    $loremHash = '2f8586076db2559d3e72a43c4ae8a1f5957abb23ca4a1f46e380dd640536eedb';
    $this->assertSame($loremHash, $hash);
});

it('throws an exception when the file that it needs to generate a content hash for does not exist', function (): void {
    $media = $this->mock(Media::class)
        ->shouldReceive('getPathRelativeToRoot')
        ->once()
        ->andReturns(fake()->text())
        ->getMock();

    $mediaContentHasher = $this->app->get(MediaContentHasher::class);
    $mediaContentHasher->hash($media);
})->throws(InvalidArgumentException::class, 'Received a created event for a media item that doesn\'t exist in the private disk.');

it('throws an exception when the content is not a string', function (): void {
    $path = fake()->sentence;

    $media = $this->mock(Media::class)
        ->shouldReceive('getPathRelativeToRoot')
        ->times(2)
        ->andReturn($path)
        ->getMock();

    Storage::shouldReceive('exists')->andReturn(true);
    Storage::shouldReceive('disk')->andReturnSelf();
    Storage::shouldReceive('get')->with($path)->andReturn(false);

    $mediaContentHasher = $this->app->get(MediaContentHasher::class);
    $mediaContentHasher->hash($media);
})->throws(InvalidArgumentException::class, 'Could not read contents of file.');
