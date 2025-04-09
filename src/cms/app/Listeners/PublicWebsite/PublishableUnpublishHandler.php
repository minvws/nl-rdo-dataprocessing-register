<?php

declare(strict_types=1);

namespace App\Listeners\PublicWebsite;

use App\Events\PublicWebsite\PublishableUnpublishEvent;
use App\Jobs\PublicWebsite\ContentGeneratorJob;
use App\Jobs\PublicWebsite\HugoWebsiteGeneratorJob;
use App\Services\PublicWebsite\PublicWebsiteFilesystem;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Str;

use function sprintf;

class PublishableUnpublishHandler
{
    public function __construct(
        private readonly PublicWebsiteFilesystem $publicWebsiteFilesystem,
    ) {
    }

    public function handle(PublishableUnpublishEvent $event): void
    {
        $path = sprintf(
            'organisatie/%s/verwerkingen/%s.html',
            $event->publishable->getOrganisation()->slug,
            Str::slug($event->publishable->getPublicIdentifier()),
        );

        $this->publicWebsiteFilesystem->deleteFile($path);

        $bus = Bus::chain([
            new ContentGeneratorJob(),
            new HugoWebsiteGeneratorJob(),
        ]);

        $bus->dispatch();
    }
}
