<?php

declare(strict_types=1);

namespace Tests\Feature\Listeners\Media;

use App\Events\Models\PublicWebsiteEvent;
use App\Models\PublicWebsite;
use Illuminate\Support\Facades\Event;

use function fake;
use function it;

it('triggers the event when created', function (): void {
    /** @var PublicWebsite $publicWebsite */
    $publicWebsite = PublicWebsite::factory()->create();

    Event::fake(PublicWebsiteEvent::class);

    $publicWebsite->home_content = fake()->sentence();
    $publicWebsite->save();

    Event::assertDispatched(PublicWebsiteEvent::class);
});
