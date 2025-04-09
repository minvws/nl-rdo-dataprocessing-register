<?php

declare(strict_types=1);

namespace App\Listeners\Media;

use App\Actions\Media\MediaContentHasher;
use App\Vendor\MediaLibrary\Media;
use Spatie\MediaLibrary\MediaCollections\Events\MediaHasBeenAddedEvent;
use Webmozart\Assert\Assert;

class MediaHasBeenAddedHandler
{
    public function __construct(
        private readonly MediaContentHasher $contentHasher,
    ) {
    }

    public function handle(MediaHasBeenAddedEvent $event): void
    {
        Assert::isInstanceOf($event->media, Media::class);

        $event->media->content_hash = $this->contentHasher->hash($event->media);
        $event->media->saveQuietly(); // only storing the contentHash-value here, don't generate new events
    }
}
