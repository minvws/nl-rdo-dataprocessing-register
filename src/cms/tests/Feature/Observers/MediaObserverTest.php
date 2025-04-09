<?php

declare(strict_types=1);

namespace Tests\Feature\Listeners\Media;

use App\Enums\Media\MediaGroup;
use App\Events\Models\OrganisationEvent;
use App\Vendor\MediaLibrary\Media;
use Illuminate\Support\Facades\Event;

use function fake;
use function it;

it('triggers the event when created', function (): void {
    Event::fake(OrganisationEvent::class);

    Media::factory()
        ->create([
            'collection_name' => MediaGroup::ORGANISATION_POSTERS->value,
        ]);

    Event::assertDispatched(OrganisationEvent::class);
});

it('triggers the event when edited', function (): void {
    $media = Media::factory()
        ->create([
            'collection_name' => MediaGroup::ORGANISATION_POSTERS->value,
            'name' => fake()->unique()->word(),
        ]);

    Event::fake(OrganisationEvent::class);

    $media->name = fake()->unique()->word();
    $media->save();

    Event::assertDispatched(OrganisationEvent::class);
});

it('triggers the event when deleted', function (): void {
    $media = Media::factory()
        ->create([
            'collection_name' => MediaGroup::ORGANISATION_POSTERS->value,
        ]);

    Event::fake(OrganisationEvent::class);
    $media->delete();

    Event::assertDispatched(OrganisationEvent::class);
});
