<?php

declare(strict_types=1);

namespace Tests\Feature\Jobs\Media;

use App\Jobs\Media\PruneMetaData;
use App\Services\Media\ExifToolService;
use App\Vendor\MediaLibrary\Media;

use function dispatch_sync;
use function it;

it('does not prune metadata if the batch is cancelled', function (): void {
    $this->mock(ExifToolService::class)
        ->shouldReceive('pruneExifData')
        ->never();

    $media = Media::factory()
        ->create();

    $pruneMetaData = (new PruneMetaData($media))->withFakeBatch();
    $job = $pruneMetaData[0];
    $batch = $pruneMetaData[1];

    $batch->cancel();

    dispatch_sync($job);
});

it('calls the ExifToolService to prune metadata', function (): void {
    $media = Media::factory()->create();

    $pruneMetaData = (new PruneMetaData($media))->withFakeBatch();
    $job = $pruneMetaData[0];

    $exifToolService = $this->mock(ExifToolService::class)
        ->shouldReceive('pruneExifData')
        ->once()
        ->with($media->getPath())
        ->getMock();

    $job->handle($exifToolService);
});
