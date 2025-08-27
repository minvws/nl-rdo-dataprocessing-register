<?php

declare(strict_types=1);

namespace Tests\Feature\Listeners\Media;

use App\Actions\Media\MediaContentHasher;
use App\Listeners\PostMediaUploadHandler;
use App\Vendor\MediaLibrary\Media;
use Illuminate\Support\Facades\Event;
use Spatie\MediaLibrary\MediaCollections\Events\MediaHasBeenAddedEvent;

use function event;
use function expect;
use function it;

it('sets the contenthash on a media item when it receives a MediaHasBeenAddedEvent', function (): void {
    Event::fakeExcept([MediaHasBeenAddedEvent::class]);

    $this->mock(PostMediaUploadHandler::class)
        ->shouldReceive('handle')
        ->once();

    $this->mock(MediaContentHasher::class)
        ->shouldReceive('hash')
        ->once()
        ->andReturn('content-hash');

    $media = Media::factory()->make([
        'content_hash' => null,
    ]);

    expect($media->content_hash)->toBeNull();

    event(new MediaHasBeenAddedEvent($media));

    expect($media->refresh()->content_hash)->toBe('content-hash');
});
