<?php

declare(strict_types=1);

namespace Tests\Feature\Listeners\Media;

use App\Events\PublicWebsite\OrganisationPublishEvent;
use App\Jobs\PublicWebsite\ContentGeneratorJob;
use App\Jobs\PublicWebsite\HugoWebsiteGeneratorJob;
use App\Jobs\PublicWebsite\OrganisationGeneratorJob;
use App\Jobs\PublicWebsite\PublishableListGeneratorJob;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\Organisation;
use App\Models\Snapshot;
use App\Models\States\Snapshot\Established;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Bus;

use function fake;
use function it;

it('stores the debounced chain', function (): void {
    Bus::fake();

    $organisation = Organisation::factory()
        ->create();
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
        ->create([
            'public_from' => CarbonImmutable::yesterday(),
        ]);
    Snapshot::factory()
        ->for($avgResponsibleProcessingRecord, 'snapshotSource')
        ->withSnapshotData([
            'public_markdown' => fake()->sentence(),
        ])
        ->create([
            'state' => Established::class,
        ]);

    OrganisationPublishEvent::dispatch($organisation);

    Bus::assertChained([
        OrganisationGeneratorJob::class,
        PublishableListGeneratorJob::class,
        ContentGeneratorJob::class,
        HugoWebsiteGeneratorJob::class,
    ]);
});
