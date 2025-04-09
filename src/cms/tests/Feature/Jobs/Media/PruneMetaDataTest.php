<?php

declare(strict_types=1);

namespace Tests\Feature\Jobs\Media;

use App\Jobs\Media\PruneMetaData;
use App\Services\Media\ExifToolService;
use App\Vendor\MediaLibrary\Media;
use Mockery;

use function dispatch_sync;
use function it;
use function mock;

it('does not prune metadata if the batch is cancelled', function (): void {
    $this->mock(ExifToolService::class, function (Mockery\MockInterface $mock): void {
        $mock->allows('pruneExifData')->never();
    });

    $media = Media::factory()
        ->create();

    [$job, $batch] = (new PruneMetaData($media))->withFakeBatch();
    $batch->cancel();

    dispatch_sync($job);
});

it('calls the ExifToolService to prune metadata', function (): void {
    $media = Media::factory()->create();

    [$job] = (new PruneMetaData($media))->withFakeBatch();

    $exifService = mock(ExifToolService::class)
        ->allows('pruneExifData')
        ->once()
        ->with($media->getPath())
        ->getMock();

    $job->handle($exifService);
});
