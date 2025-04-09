<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Jobs\Media\PostMediaUploadJobChain;
use App\Vendor\MediaLibrary\Media;
use Spatie\MediaLibrary\MediaCollections\Events\MediaHasBeenAddedEvent;
use Webmozart\Assert\Assert;

class PostMediaUploadHandler
{
    public function __construct(
        private readonly PostMediaUploadJobChain $postMediaUploadJobChain,
    ) {
    }

    public function handle(MediaHasBeenAddedEvent $event): void
    {
        Assert::isInstanceOf($event->media, Media::class);

        $this->postMediaUploadJobChain->run($event->media);
    }
}
