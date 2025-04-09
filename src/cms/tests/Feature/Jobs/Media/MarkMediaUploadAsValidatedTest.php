<?php

declare(strict_types=1);

use App\Jobs\Media\MarkMediaUploadAsValidated;
use App\Vendor\MediaLibrary\Media;

use function PHPUnit\Framework\assertEmpty;
use function PHPUnit\Framework\assertNull;
use function PHPUnit\Framework\assertTrue;

it('validates the media upload', function (): void {
    $media = Media::factory()
        ->create([
            'updated_at' => now()->subSeconds(10),
            'validated_at' => null,
        ]);

    $job = new MarkMediaUploadAsValidated($media);

    $job->handle();
    $media->refresh();

    expect($media->validated_at)
        ->not()->toBeNull();
});

it('does not validate the media upload if it has already been validated', function (): void {
    $validatedAt = now()->subSeconds(5);
    $media = Media::factory()
        ->create([
            'updated_at' => now()->subSeconds(10),
            'validated_at' => $validatedAt,
        ]);

    $job = new MarkMediaUploadAsValidated($media);

    $job->handle();
    $media->refresh();

    expect($media->validated_at)->not()->toBeNull()
        ->and($media->validated_at->toString())->toBe($validatedAt->toString());
});

it('does not run the job if the batch has been cancelled', function (): void {
    $media = Media::factory()
        ->create([
            'updated_at' => now()->subSeconds(10),
            'validated_at' => null,
        ]);

    [$job, $batch] = (new MarkMediaUploadAsValidated($media))->withFakeBatch();
    $batch->cancel();

    dispatch_sync($job);

    assertTrue($batch->cancelled());
    assertEmpty($batch->added);
    assertNull($media->validated_at);
});
