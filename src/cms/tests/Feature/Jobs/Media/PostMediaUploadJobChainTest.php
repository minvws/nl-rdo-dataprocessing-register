<?php

declare(strict_types=1);

use App\Jobs\Media\InvalidMimeTypeException;
use App\Jobs\Media\MarkMediaUploadAsValidated;
use App\Jobs\Media\PruneMetaData;
use App\Jobs\Media\ValidateMimeType;
use App\Listeners\Media\MediaHasBeenAddedHandler;
use App\Vendor\MediaLibrary\Media;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use Spatie\MediaLibrary\MediaCollections\Events\MediaHasBeenAddedEvent;
use Tests\Feature\Jobs\Media\ThrowExceptionJob;
use Tests\Helpers\ConfigTestHelper;

it('can run the chain', function (): void {
    Bus::fake();
    Event::fakeExcept([MediaHasBeenAddedEvent::class]);

    $this->mock(MediaHasBeenAddedHandler::class)
        ->shouldReceive('handle')
        ->once();

    $media = Media::factory()
        ->create();

    ConfigTestHelper::set('media-library.post_media_upload_job_chain', [
        PruneMetaData::class,
        ValidateMimeType::class,
        MarkMediaUploadAsValidated::class,
    ]);

    event(new MediaHasBeenAddedEvent($media));

    Bus::assertChained([
        new PruneMetaData($media),
        new ValidateMimeType($media),
        new MarkMediaUploadAsValidated($media),
    ]);
});

it('deletes the media when an exception is thrown in the chain', function (): void {
    $uuid = fake()->uuid();
    $media = Media::factory()
        ->create(['uuid' => $uuid]);

    Event::fakeExcept([MediaHasBeenAddedEvent::class]);
    ConfigTestHelper::set('media-library.post_media_upload_job_chain', [
        ThrowExceptionJob::class,
    ]);

    expect(function () use ($media): void {
        event(new MediaHasBeenAddedEvent($media));
    })->toThrow(InvalidMimeTypeException::class);

    $mediaCount = Media::query()
        ->where(['uuid' => $uuid])
        ->count();
    expect($mediaCount)
        ->toBe(0);
});
