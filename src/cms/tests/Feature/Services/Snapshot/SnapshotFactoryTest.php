<?php

declare(strict_types=1);

use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\Snapshot;
use App\Models\States\Snapshot\Approved;
use App\Models\States\Snapshot\Established;
use App\Models\States\Snapshot\InReview;
use App\Models\States\Snapshot\Obsolete;
use App\Models\States\SnapshotState;
use App\Models\User;
use App\Services\Snapshot\SnapshotFactory;

it('creates the snapshot', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()->create();

    expect($avgResponsibleProcessingRecord->snapshots()->count())
        ->toBe(0);

    /** @var SnapshotFactory $snapshotFactory */
    $snapshotFactory = $this->app->get(SnapshotFactory::class);
    $snapshotFactory->fromSnapshotSource($avgResponsibleProcessingRecord);

    expect($avgResponsibleProcessingRecord->snapshots()->count())
        ->toBe(1);
});

it('creates the snapshot and transitions existing with default state', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()->create();
    Snapshot::factory()
        ->create([
            'snapshot_source_id' => $avgResponsibleProcessingRecord->id,
            'snapshot_source_type' => AvgResponsibleProcessingRecord::class,
            'state' => SnapshotState::DEFAULT_STATE,
        ]);

    expect($avgResponsibleProcessingRecord->snapshots()->count())
        ->toBe(1);

    $this->be(User::factory()->create());

    $snapshotFactory = $this->app->get(SnapshotFactory::class);
    $snapshotFactory->fromSnapshotSource($avgResponsibleProcessingRecord);

    expect($avgResponsibleProcessingRecord->snapshots()->count())
        ->toBe(2);
});

it('creates the snapshot with correct state', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()->create();
    $state = fake()->randomElement([
        InReview::class,
        Approved::class,
        Established::class,
        Obsolete::class,
    ]);

    $this->be(User::factory()->create());

    $snapshotFactory = $this->app->get(SnapshotFactory::class);
    $snapshot = $snapshotFactory->fromSnapshotSource($avgResponsibleProcessingRecord, $state);

    expect($snapshot->version)->toBe(1)
        ->and($snapshot->state::class)->toBe($state);
});


it('creates the snapshot with correct state and version', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()->create();
    $state = fake()->randomElement([
        InReview::class,
        Approved::class,
        Established::class,
        Obsolete::class,
    ]);
    $version = fake()->randomNumber();

    $this->be(User::factory()->create());

    $snapshotFactory = $this->app->get(SnapshotFactory::class);
    $snapshot = $snapshotFactory->fromSnapshotSource($avgResponsibleProcessingRecord, $state, $version);

    expect($snapshot->version)->toBe($version)
        ->and($snapshot->state::class)->toBe($state);
});
