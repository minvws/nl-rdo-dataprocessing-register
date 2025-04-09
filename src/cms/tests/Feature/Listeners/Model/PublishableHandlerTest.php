<?php

declare(strict_types=1);

namespace Tests\Feature\Listeners\Model;

use App\Events\PublicWebsite\OrganisationPublishEvent;
use App\Events\PublicWebsite\OrganisationUnpublishEvent;
use App\Events\PublicWebsite\PublishablePublishEvent;
use App\Events\PublicWebsite\PublishableUnpublishEvent;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Event;

use function it;

it('triggers the publish-event when record is created', function (): void {
    Event::fake([
        OrganisationPublishEvent::class,
        OrganisationUnpublishEvent::class,
        PublishablePublishEvent::class,
        PublishableUnpublishEvent::class,
    ]);

    AvgResponsibleProcessingRecord::factory()
        ->create([
            'public_from' => CarbonImmutable::yesterday(),
        ]);

    Event::assertDispatched(PublishablePublishEvent::class);
    Event::assertNotDispatched(PublishableUnpublishEvent::class);
});

it('triggers the unpublish-event when record is deleted', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->create();

    Event::fake([
        PublishableUnpublishEvent::class,
        PublishablePublishEvent::class,
    ]);

    $avgResponsibleProcessingRecord->delete();

    Event::assertNotDispatched(PublishablePublishEvent::class);
    Event::assertDispatched(PublishableUnpublishEvent::class);
});

it('triggers the unpublish-event when public_from is set to null', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->create([
            'public_from' => CarbonImmutable::yesterday(),
        ]);

    Event::fake([
        PublishableUnpublishEvent::class,
        PublishablePublishEvent::class,
    ]);

    $avgResponsibleProcessingRecord->public_from = null;
    $avgResponsibleProcessingRecord->save();

    Event::assertNotDispatched(PublishablePublishEvent::class);
    Event::assertDispatched(PublishableUnpublishEvent::class);
});

it('triggers the unpublish-event when public_from is set in the future', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->create([
            'public_from' => CarbonImmutable::yesterday(),
        ]);

    Event::fake([
        PublishableUnpublishEvent::class,
        PublishablePublishEvent::class,
    ]);

    $avgResponsibleProcessingRecord->public_from = CarbonImmutable::tomorrow();
    $avgResponsibleProcessingRecord->save();

    Event::assertNotDispatched(PublishablePublishEvent::class);
    Event::assertDispatched(PublishableUnpublishEvent::class);
});
