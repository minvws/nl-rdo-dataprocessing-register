<?php

declare(strict_types=1);

use App\Enums\Snapshot\SnapshotApprovalStatus;
use App\Filament\Actions\SnapshotTransition\EstablishAction;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\Processor;
use App\Models\RelatedSnapshotSource;
use App\Models\Snapshot;
use App\Models\SnapshotApproval;
use App\Models\States\Snapshot\Established;
use App\Models\States\Snapshot\InReview;
use App\Models\States\SnapshotState;

it('can make the action', function (): void {
    $snapshot = Snapshot::factory()->create();
    $snapshotState = SnapshotState::make(Established::$name, $snapshot);
    $establishAction = EstablishAction::makeForSnapshotState($snapshot, $snapshotState);

    expect($establishAction)
        ->toBeInstanceOf(EstablishAction::class);
});

it('is in warning color if no approvals', function (): void {
    $snapshot = Snapshot::factory()->create();
    $snapshotState = SnapshotState::make(Established::$name, $snapshot);
    $establishAction = EstablishAction::makeForSnapshotState($snapshot, $snapshotState);

    expect($establishAction->getColor())
        ->toBe('warning');
});

it('is in warning color if 1 approval, but declined', function (): void {
    $snapshot = Snapshot::factory()->create();
    SnapshotApproval::factory()
        ->for($snapshot)
        ->create([
            'status' => SnapshotApprovalStatus::DECLINED,
        ]);
    $snapshotState = SnapshotState::make(Established::$name, $snapshot);
    $establishAction = EstablishAction::makeForSnapshotState($snapshot, $snapshotState);

    expect($establishAction->getColor())
        ->toBe('warning');
});

it('is in warning color if approved but non-established related snapshot sources', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()->create();
    $avgResponsibleProcessingRecordSnapshot = Snapshot::factory()
        ->create([
            'snapshot_source_id' => $avgResponsibleProcessingRecord->id,
            'snapshot_source_type' => AvgResponsibleProcessingRecord::class,
        ]);
    SnapshotApproval::factory()
        ->for($avgResponsibleProcessingRecordSnapshot)
        ->create([
            'status' => SnapshotApprovalStatus::APPROVED,
        ]);

    $processor = Processor::factory()->create();
    Snapshot::factory()
        ->create([
            'state' => InReview::$name,
            'snapshot_source_id' => $processor->id,
            'snapshot_source_type' => Processor::class,
        ]);
    RelatedSnapshotSource::factory()
        ->create([
            'snapshot_id' => $avgResponsibleProcessingRecordSnapshot->id,
            'snapshot_source_id' => $processor->id,
            'snapshot_source_type' => Processor::class,
        ]);

    $snapshotState = SnapshotState::make(Established::$name, $avgResponsibleProcessingRecordSnapshot);
    $establishAction = EstablishAction::makeForSnapshotState($avgResponsibleProcessingRecordSnapshot, $snapshotState);

    expect($establishAction->getColor())
        ->toBe('warning');
});

it('is in success color if approved and an established related snapshot sources', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()->create();
    $avgResponsibleProcessingRecordSnapshot = Snapshot::factory()
        ->create([
            'snapshot_source_id' => $avgResponsibleProcessingRecord->id,
            'snapshot_source_type' => AvgResponsibleProcessingRecord::class,
        ]);
    SnapshotApproval::factory()
        ->for($avgResponsibleProcessingRecordSnapshot)
        ->create([
            'status' => SnapshotApprovalStatus::APPROVED,
        ]);

    $processor = Processor::factory()->create();
    Snapshot::factory()
        ->create([
            'state' => Established::$name,
            'snapshot_source_id' => $processor->id,
            'snapshot_source_type' => Processor::class,
        ]);
    RelatedSnapshotSource::factory()
        ->create([
            'snapshot_id' => $avgResponsibleProcessingRecordSnapshot->id,
            'snapshot_source_id' => $processor->id,
            'snapshot_source_type' => Processor::class,
        ]);

    $snapshotState = SnapshotState::make(Established::$name, $avgResponsibleProcessingRecordSnapshot);
    $establishAction = EstablishAction::makeForSnapshotState($avgResponsibleProcessingRecordSnapshot, $snapshotState);

    expect($establishAction->getColor())
        ->toBe('success');
});

it('is in success color if approved and no related snapshot sources', function (): void {
    $snapshot = Snapshot::factory()->create();
    SnapshotApproval::factory()
        ->for($snapshot)
        ->create([
            'status' => SnapshotApprovalStatus::APPROVED,
        ]);
    $snapshotState = SnapshotState::make(Established::$name, $snapshot);
    $establishAction = EstablishAction::makeForSnapshotState($snapshot, $snapshotState);

    expect($establishAction->getColor())
        ->toBe('success');
});
