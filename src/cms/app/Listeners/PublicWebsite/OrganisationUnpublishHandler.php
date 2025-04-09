<?php

declare(strict_types=1);

namespace App\Listeners\PublicWebsite;

use App\Events\PublicWebsite\OrganisationUnpublishEvent;
use App\Jobs\PublicWebsite\ContentGeneratorJob;
use App\Jobs\PublicWebsite\HugoWebsiteGeneratorJob;
use App\Services\PublicWebsite\PublicWebsiteFilesystem;
use Illuminate\Support\Facades\Bus;

use function sprintf;

class OrganisationUnpublishHandler
{
    public function __construct(
        private readonly PublicWebsiteFilesystem $publicWebsiteFilesystem,
    ) {
    }

    public function handle(OrganisationUnpublishEvent $event): void
    {
        $this->publicWebsiteFilesystem->deleteDirectory(sprintf('organisatie/%s', $event->organisation->slug));

        $bus = Bus::chain([
            new ContentGeneratorJob(),
            new HugoWebsiteGeneratorJob(),
        ]);

        $bus->dispatch();
    }
}
