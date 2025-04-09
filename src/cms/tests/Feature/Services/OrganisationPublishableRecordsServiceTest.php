<?php

declare(strict_types=1);

use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\Organisation;
use App\Models\Snapshot;
use App\Models\States\Snapshot\Approved;
use App\Models\States\Snapshot\Established;
use App\Services\OrganisationPublishableRecordsService;
use Carbon\CarbonImmutable;

it('can get a publishable record', function (): void {
    $organisation = Organisation::factory()
        ->create([
            'public_from' => CarbonImmutable::yesterday(),
        ]);
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
        ->create([
            'public_from' => CarbonImmutable::yesterday(),
        ]);
    Snapshot::factory()
        ->for($avgResponsibleProcessingRecord, 'snapshotSource')
        ->recycle($organisation)
        ->withSnapshotData([
            'public_markdown' => fake()->sentence(),
        ])
        ->create([
            'state' => Established::class,
        ]);

    /** @var OrganisationPublishableRecordsService $organisationPublishableRecordsService */
    $organisationPublishableRecordsService = $this->app->get(OrganisationPublishableRecordsService::class);
    $publishables = $organisationPublishableRecordsService->getPublishableRecords($organisation);

    expect($publishables->count())
        ->toBe(1);
});

it('will not get a record wihtout a public_from date', function (): void {
    $organisation = Organisation::factory()
        ->create([
            'public_from' => CarbonImmutable::yesterday(),
        ]);
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
        ->create([
            'public_from' => null,
        ]);
    Snapshot::factory()
        ->for($avgResponsibleProcessingRecord, 'snapshotSource')
        ->recycle($organisation)
        ->withSnapshotData([
            'public_markdown' => fake()->sentence(),
        ])
        ->create([
            'state' => Established::class,
        ]);

    /** @var OrganisationPublishableRecordsService $organisationPublishableRecordsService */
    $organisationPublishableRecordsService = $this->app->get(OrganisationPublishableRecordsService::class);
    $publishables = $organisationPublishableRecordsService->getPublishableRecords($organisation);

    expect($publishables->count())
        ->toBe(0);
});

it('will not get a record with a non-established state', function (): void {
    $organisation = Organisation::factory()
        ->create([
            'public_from' => CarbonImmutable::yesterday(),
        ]);
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
        ->create([
            'public_from' => CarbonImmutable::yesterday(),
        ]);
    Snapshot::factory()
        ->for($avgResponsibleProcessingRecord, 'snapshotSource')
        ->recycle($organisation)
        ->withSnapshotData([
            'public_markdown' => fake()->sentence(),
        ])
        ->create([
            'state' => Approved::class,
        ]);

    /** @var OrganisationPublishableRecordsService $organisationPublishableRecordsService */
    $organisationPublishableRecordsService = $this->app->get(OrganisationPublishableRecordsService::class);
    $publishables = $organisationPublishableRecordsService->getPublishableRecords($organisation);

    expect($publishables->count())
        ->toBe(0);
});

it('will not get a record without snapshot-data', function (): void {
    $organisation = Organisation::factory()
        ->create([
            'public_from' => CarbonImmutable::yesterday(),
        ]);
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
        ->create([
            'public_from' => CarbonImmutable::yesterday(),
        ]);
    Snapshot::factory()
        ->for($avgResponsibleProcessingRecord, 'snapshotSource')
        ->recycle($organisation)
        ->create([
            'state' => Established::class,
        ]);

    /** @var OrganisationPublishableRecordsService $organisationPublishableRecordsService */
    $organisationPublishableRecordsService = $this->app->get(OrganisationPublishableRecordsService::class);
    $publishables = $organisationPublishableRecordsService->getPublishableRecords($organisation);

    expect($publishables->count())
        ->toBe(0);
});

it('will not get a record with a valid snapshot but without public-markdown in the snapshot-data', function (): void {
    $organisation = Organisation::factory()
        ->create([
            'public_from' => CarbonImmutable::yesterday(),
        ]);
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
        ->create([
            'public_from' => CarbonImmutable::yesterday(),
        ]);
    Snapshot::factory()
        ->for($avgResponsibleProcessingRecord, 'snapshotSource')
        ->recycle($organisation)
        ->withSnapshotData([
            'public_markdown' => null,
        ])
        ->create([
            'state' => Established::class,
        ]);

    /** @var OrganisationPublishableRecordsService $organisationPublishableRecordsService */
    $organisationPublishableRecordsService = $this->app->get(OrganisationPublishableRecordsService::class);
    $publishables = $organisationPublishableRecordsService->getPublishableRecords($organisation);

    expect($publishables->count())
        ->toBe(0);
});
