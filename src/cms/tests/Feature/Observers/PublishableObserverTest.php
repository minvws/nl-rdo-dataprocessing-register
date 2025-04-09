<?php

declare(strict_types=1);

namespace Tests\Feature\Listeners\Media;

use App\Events\Models\PublishableEvent;
use App\Events\PublicWebsite\OrganisationPublishEvent;
use App\Events\PublicWebsite\OrganisationUnpublishEvent;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Event;

use function it;

it('triggers the event when created', function (): void {
    Event::fake([
        OrganisationPublishEvent::class,
        OrganisationUnpublishEvent::class,
        PublishableEvent::class,
    ]);

    AvgResponsibleProcessingRecord::factory()
        ->create();

    Event::assertDispatched(PublishableEvent::class);
});

it('triggers the event when edited', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->create([
            'public_from' => null,
        ]);

    Event::fake(PublishableEvent::class);

    $avgResponsibleProcessingRecord->public_from = CarbonImmutable::yesterday();
    $avgResponsibleProcessingRecord->save();

    Event::assertDispatched(PublishableEvent::class);
});

it('triggers the event when deleted', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->create();

    Event::fake([
        OrganisationPublishEvent::class,
        OrganisationUnpublishEvent::class,
        PublishableEvent::class,
    ]);

    $avgResponsibleProcessingRecord->delete();

    Event::assertDispatched(PublishableEvent::class);
});
