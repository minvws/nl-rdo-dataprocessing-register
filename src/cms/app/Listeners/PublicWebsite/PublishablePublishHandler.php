<?php

declare(strict_types=1);

namespace App\Listeners\PublicWebsite;

use App\Events\PublicWebsite\PublishablePublishEvent;
use App\Jobs\PublicWebsite\ContentGeneratorJob;
use App\Jobs\PublicWebsite\HugoWebsiteGeneratorJob;
use App\Jobs\PublicWebsite\PublishableGeneratorJob;
use App\Jobs\PublicWebsite\PublishableListGeneratorJob;
use Illuminate\Support\Facades\Bus;

class PublishablePublishHandler
{
    public function handle(PublishablePublishEvent $event): void
    {
        Bus::chain([
            new PublishableListGeneratorJob($event->publishable->getOrganisation()),
            new PublishableGeneratorJob($event->publishable),
            new ContentGeneratorJob(),
            new HugoWebsiteGeneratorJob(),
        ])->dispatch();
    }
}
