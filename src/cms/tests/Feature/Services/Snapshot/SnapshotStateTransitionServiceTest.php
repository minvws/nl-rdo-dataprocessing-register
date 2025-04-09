<?php

declare(strict_types=1);

use App\Enums\Authorization\Role;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\Organisation;
use App\Models\Snapshot;
use App\Models\States\Snapshot\Approved;
use App\Models\States\Snapshot\Established;
use App\Models\States\Snapshot\InReview;
use App\Models\States\Snapshot\Obsolete;
use App\Models\States\SnapshotState;
use App\Models\User;
use App\Services\Snapshot\SnapshotStateTransitionService;

it('makes previous snapshots (only of the same state) obsolete', function (): void {
    Event::fake();

    $organisation = Organisation::factory()->create();
    $user = User::factory()
        ->hasAttached($organisation)
        ->create();
    $user->assignOrganisationRole(Role::PRIVACY_OFFICER, $organisation);
    $this->be($user);

    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()->create();
    $snapshot1 = Snapshot::factory()->create([
        'snapshot_source_type' => AvgResponsibleProcessingRecord::class,
        'snapshot_source_id' => $avgResponsibleProcessingRecord->id,
        'state' => Established::class,
    ]);
    $snapshot2 = Snapshot::factory()->create([
        'snapshot_source_type' => AvgResponsibleProcessingRecord::class,
        'snapshot_source_id' => $avgResponsibleProcessingRecord->id,
        'state' => Approved::class,
    ]);
    $snapshot3 = Snapshot::factory()->create([
        'snapshot_source_type' => AvgResponsibleProcessingRecord::class,
        'snapshot_source_id' => $avgResponsibleProcessingRecord->id,
        'state' => Obsolete::class,
    ]);
    $snapshot4 = Snapshot::factory()->create([
        'snapshot_source_type' => AvgResponsibleProcessingRecord::class,
        'snapshot_source_id' => $avgResponsibleProcessingRecord->id,
        'state' => InReview::class,
    ]);

    /** @var SnapshotStateTransitionService $snapshotStateTransitionService */
    $snapshotStateTransitionService = $this->app->get(SnapshotStateTransitionService::class);
    /** @var SnapshotState $snapshotState */
    $snapshotState = SnapshotState::make(Approved::class, $snapshot1);
    $snapshotStateTransitionService->transitionToSnapshotState($snapshot4, $snapshotState);

    expect($snapshot1->refresh()->state)
        ->toBeInstanceOf(Established::class)
        ->and($snapshot2->refresh()->state)
        ->toBeInstanceOf(Obsolete::class)
        ->and($snapshot3->refresh()->state)
        ->toBeInstanceOf(Obsolete::class)
        ->and($snapshot4->refresh()->state)
        ->toBeInstanceOf(Approved::class);
});

it('does not obsolete snapshots which are already obsolete', function (): void {
    Event::fake();

    $organisation = Organisation::factory()->create();
    $user = User::factory()
        ->hasAttached($organisation)
        ->create();
    $user->assignOrganisationRole(Role::PRIVACY_OFFICER, $organisation);
    $this->be($user);

    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()->create();
    $snapshot1 = Snapshot::factory()->create([
        'snapshot_source_type' => AvgResponsibleProcessingRecord::class,
        'snapshot_source_id' => $avgResponsibleProcessingRecord->id,
        'state' => Established::class,
    ]);
    $snapshot2 = Snapshot::factory()->create([
        'snapshot_source_type' => AvgResponsibleProcessingRecord::class,
        'snapshot_source_id' => $avgResponsibleProcessingRecord->id,
        'state' => Obsolete::class,
    ]);

    /** @var SnapshotStateTransitionService $snapshotStateTransitionService */
    $snapshotStateTransitionService = $this->app->get(SnapshotStateTransitionService::class);
    /** @var SnapshotState $snapshotState */
    $snapshotState = SnapshotState::make(Obsolete::class, $snapshot1);
    $snapshotStateTransitionService->transitionToSnapshotState($snapshot1, $snapshotState);

    expect($snapshot1->refresh()->state)
        ->toBeInstanceOf(Obsolete::class)
        ->and($snapshot2->refresh()->state)
        ->toBeInstanceOf(Obsolete::class);
});

it('sets replaced at attribute when snapshot is obsoleted', function (): void {
    Event::fake();

    $organisation = Organisation::factory()->create();
    $user = User::factory()
        ->hasAttached($organisation)
        ->create();
    $user->assignOrganisationRole(Role::PRIVACY_OFFICER, $organisation);
    $this->be($user);

    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()->create();
    $snapshot = Snapshot::factory()->create([
        'snapshot_source_type' => AvgResponsibleProcessingRecord::class,
        'snapshot_source_id' => $avgResponsibleProcessingRecord->id,
        'state' => Established::class,
        'replaced_at' => null,
    ]);

    /** @var SnapshotStateTransitionService $snapshotStateTransitionService */
    $snapshotStateTransitionService = $this->app->get(SnapshotStateTransitionService::class);

    /** @var SnapshotState $snapshotState */
    $snapshotState = SnapshotState::make(Obsolete::class, $snapshot);
    $snapshotStateTransitionService->transitionToSnapshotState($snapshot, $snapshotState);
    expect($snapshot->refresh()->replaced_at)->not()->toBeNull();
});
