<?php

declare(strict_types=1);

namespace Tests\Feature\Listeners\Media;

use App\Events\StaticWebsite\BuildEvent;
use App\Models\PublicWebsite;
use Illuminate\Support\Facades\Event;

use function fake;
use function it;

it('dispatches the build-event if public-website is created', function (): void {
    Event::fake(BuildEvent::class);

    PublicWebsite::factory()->create();

    Event::assertDispatched(BuildEvent::class);
});

it('dispatches the build-event if public-website is edited', function (): void {
    $publicWebsite = PublicWebsite::factory()->createQuietly();

    Event::fake(BuildEvent::class);

    $publicWebsite->home_content = fake()->sentence();
    $publicWebsite->save();

    Event::assertDispatched(BuildEvent::class);
});

it('does not dispatch the build-event if public-website is deleted', function (): void {
    $publicWebsite = PublicWebsite::factory()->createQuietly();

    Event::fake(BuildEvent::class);

    $publicWebsite->delete();

    Event::assertNotDispatched(BuildEvent::class);
});
