<?php

declare(strict_types=1);

namespace Tests\Feature\Listeners\Media;

use App\Enums\Media\MediaGroup;
use App\Events\PublicWebsite;
use App\Events\StaticWebsite;
use App\Models\Document;
use App\Models\Organisation;
use App\Vendor\MediaLibrary\Media;
use Illuminate\Support\Facades\Event;

use function fake;
use function it;

it('dispatches the build-event when needed if media-object is created', function (MediaGroup $mediaGroup, bool $expectedEvent): void {
    Event::fake([
        PublicWebsite\BuildEvent::class,
        StaticWebsite\BuildEvent::class,
    ]);

    $organisation = Organisation::factory()->createQuietly();
    $media = Media::factory()
        ->for($organisation)
        ->recycle($organisation)
        ->make([
            'model_type' => Document::class,
            'model_id' => Document::factory(),
            'collection_name' => $mediaGroup->value,
        ]);
    $media->save();

    Event::assertDispatchedTimes(PublicWebsite\BuildEvent::class, (int) $expectedEvent);
    Event::assertDispatchedTimes(StaticWebsite\BuildEvent::class, (int) $expectedEvent);
})->with([
    'attachments' => [MediaGroup::ATTACHMENTS, false],
    'public_website_tree' => [MediaGroup::PUBLIC_WEBSITE_TREE, false],
    'organisation_posters' => [MediaGroup::ORGANISATION_POSTERS, true],
]);

it('dispatches the build-event when needed if media-object is updated', function (MediaGroup $mediaGroup, bool $expectedEvent): void {
    $media = Media::factory()
        ->createQuietly([
            'collection_name' => $mediaGroup->value,
            'name' => fake()->unique()->word(),
        ]);

    Event::fake([
        PublicWebsite\BuildEvent::class,
        StaticWebsite\BuildEvent::class,
    ]);

    $media->name = fake()->unique()->word();
    $media->save();

    Event::assertDispatchedTimes(PublicWebsite\BuildEvent::class, (int) $expectedEvent);
    Event::assertDispatchedTimes(StaticWebsite\BuildEvent::class, (int) $expectedEvent);
})->with([
    'attachments' => [MediaGroup::ATTACHMENTS, false],
    'public_website_tree' => [MediaGroup::PUBLIC_WEBSITE_TREE, false],
    'organisation_posters' => [MediaGroup::ORGANISATION_POSTERS, true],
]);

it('dispatches the build-event when needed if media-object is deleted', function (MediaGroup $mediaGroup, bool $expectedEvent): void {
    $media = Media::factory()
        ->createQuietly([
            'collection_name' => $mediaGroup->value,
        ]);

    Event::fake([
        PublicWebsite\BuildEvent::class,
        StaticWebsite\BuildEvent::class,
    ]);

    $media->delete();

    Event::assertDispatchedTimes(PublicWebsite\BuildEvent::class, (int) $expectedEvent);
    Event::assertDispatchedTimes(StaticWebsite\BuildEvent::class, (int) $expectedEvent);
})->with([
    'attachments' => [MediaGroup::ATTACHMENTS, false],
    'public_website_tree' => [MediaGroup::PUBLIC_WEBSITE_TREE, false],
    'organisation_posters' => [MediaGroup::ORGANISATION_POSTERS, true],
]);
