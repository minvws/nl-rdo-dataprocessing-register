<?php

declare(strict_types=1);

namespace Tests\Feature\Listeners\Media;

use App\Events\PublicWebsite as PublicWebsiteEvents;
use App\Events\StaticWebsite;
use App\Models\PublicWebsite;
use Illuminate\Support\Facades\Event;

use function fake;
use function it;

it('dispatches the build-event if public-website is created', function (): void {
    Event::fake([
        PublicWebsiteEvents\BuildEvent::class,
        StaticWebsite\BuildEvent::class,
    ]);

    PublicWebsite::factory()->create();

    Event::assertDispatched(PublicWebsiteEvents\BuildEvent::class);
    Event::assertDispatched(StaticWebsite\BuildEvent::class);
});

it('dispatches the build-event if public-website is edited', function (): void {
    $publicWebsite = PublicWebsite::factory()->createQuietly();

    Event::fake([
        PublicWebsiteEvents\BuildEvent::class,
        StaticWebsite\BuildEvent::class,
    ]);

    $publicWebsite->home_content = fake()->sentence();
    $publicWebsite->save();

    Event::assertDispatched(PublicWebsiteEvents\BuildEvent::class);
    Event::assertDispatched(StaticWebsite\BuildEvent::class);
});

it('does not dispatch the build-event if public-website is deleted', function (): void {
    $publicWebsite = PublicWebsite::factory()->createQuietly();

    Event::fake([
        PublicWebsiteEvents\BuildEvent::class,
        StaticWebsite\BuildEvent::class,
    ]);

    $publicWebsite->delete();

    Event::assertNotDispatched(PublicWebsiteEvents\BuildEvent::class);
    Event::assertNotDispatched(StaticWebsite\BuildEvent::class);
});
