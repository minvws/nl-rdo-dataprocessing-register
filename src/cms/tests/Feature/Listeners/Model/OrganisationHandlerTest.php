<?php

declare(strict_types=1);

namespace Tests\Feature\Listeners\Media;

use App\Events\PublicWebsite\OrganisationPublishEvent;
use App\Events\PublicWebsite\OrganisationUnpublishEvent;
use App\Models\Organisation;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Event;

use function fake;
use function it;

it('triggers the publish-event when organisation is created', function (): void {
    Event::fake(OrganisationPublishEvent::class);

    Organisation::factory()
        ->create([
            'public_from' => CarbonImmutable::yesterday(),
        ]);

    Event::assertNotDispatched(OrganisationUnpublishEvent::class);
    Event::assertDispatched(OrganisationPublishEvent::class);
});

it('triggers the publish-event when organisation is edited', function (): void {
    Event::fake(OrganisationPublishEvent::class);

    $organisation = Organisation::factory()
        ->create([
            'public_from' => CarbonImmutable::yesterday(),
        ]);

    $organisation->name = fake()->uuid();
    $organisation->save();

    Event::assertNotDispatched(OrganisationUnpublishEvent::class);
    Event::assertDispatched(OrganisationPublishEvent::class);
});

it('triggers the unpublish-event when organisation is deleted', function (): void {
    Event::fake(OrganisationUnpublishEvent::class);

    $organisation = Organisation::factory()
        ->create();

    $organisation->delete();

    Event::assertDispatched(OrganisationUnpublishEvent::class);
    Event::assertNotDispatched(OrganisationPublishEvent::class);
});

it('triggers the unpublish-event when public_from is set to null', function (): void {
    Event::fake(OrganisationUnpublishEvent::class);

    $organisation = Organisation::factory()
        ->create([
            'public_from' => CarbonImmutable::yesterday(),
        ]);

    $organisation->public_from = null;
    $organisation->save();

    Event::assertDispatched(OrganisationUnpublishEvent::class);
    Event::assertNotDispatched(OrganisationPublishEvent::class);
});

it('triggers the unpublish-event when public_from is set in the future', function (): void {
    Event::fake(OrganisationUnpublishEvent::class);

    $organisation = Organisation::factory()
        ->create([
            'public_from' => CarbonImmutable::tomorrow(),
        ]);

    $organisation->public_from = null;
    $organisation->save();

    Event::assertDispatched(OrganisationUnpublishEvent::class);
    Event::assertNotDispatched(OrganisationPublishEvent::class);
});
