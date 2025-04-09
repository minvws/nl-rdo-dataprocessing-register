<?php

declare(strict_types=1);

namespace App\Listeners\PublicWebsite;

use App\Events\PublicWebsite\OrganisationPublishEvent;
use App\Jobs\PublicWebsite\ContentGeneratorJob;
use App\Jobs\PublicWebsite\HugoWebsiteGeneratorJob;
use App\Jobs\PublicWebsite\OrganisationGeneratorJob;
use App\Jobs\PublicWebsite\PublishableListGeneratorJob;
use Illuminate\Support\Facades\Bus;

class OrganisationPublishHandler
{
    public function handle(OrganisationPublishEvent $event): void
    {
        $bus = Bus::chain([
            new OrganisationGeneratorJob($event->organisation),
            new PublishableListGeneratorJob($event->organisation),
            new ContentGeneratorJob(),
            new HugoWebsiteGeneratorJob(),
        ]);

        $bus->dispatch();
    }
}
