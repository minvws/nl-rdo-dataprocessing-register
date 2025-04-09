<?php

declare(strict_types=1);

use App\Livewire\Snapshot\RelatedSnapshotSources;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\Processor;
use App\Models\RelatedSnapshotSource;
use App\Models\Snapshot;
use App\Models\States\Snapshot\Approved;
use App\Models\States\Snapshot\Established;
use App\Models\States\Snapshot\InReview;
use App\Models\States\Snapshot\Obsolete;

use function Pest\Livewire\livewire;

it('can load the table', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->create();
    $snapshot = Snapshot::factory()
        ->for($avgResponsibleProcessingRecord, 'snapshotSource')
        ->recycle($this->organisation)
        ->create();

    // no snapshot
    $processor = Processor::factory()
        ->recycle($this->organisation)
        ->create();
    $relatedSnapshotSource = RelatedSnapshotSource::factory()
        ->for($snapshot)
        ->create([
            'snapshot_id' => $snapshot->id,
            'snapshot_source_id' => $processor->id,
            'snapshot_source_type' => Processor::class,
        ]);

    // no snapshot and deleted
    $deletedProcessor = Processor::factory()
        ->recycle($this->organisation)
        ->create(['deleted_at' => fake()->dateTime()]);
    $deletedRelatedSnapshotSource = RelatedSnapshotSource::factory()
        ->for($snapshot)
        ->create([
            'snapshot_id' => $snapshot->id,
            'snapshot_source_id' => $deletedProcessor->id,
            'snapshot_source_type' => Processor::class,
        ]);

    // established
    $establishedProcessor = Processor::factory()
        ->recycle($this->organisation)
        ->create();
    $establishedProcessorSnapshotSource = RelatedSnapshotSource::factory()
        ->for($snapshot)
        ->create([
            'snapshot_id' => $snapshot->id,
            'snapshot_source_id' => $establishedProcessor->id,
            'snapshot_source_type' => Processor::class,
        ]);
    Snapshot::factory()
        ->for($establishedProcessor, 'snapshotSource')
        ->recycle($this->organisation)
        ->create(['state' => Established::class]);

    // Approved
    $approvedProcessor = Processor::factory()
        ->recycle($this->organisation)
        ->create();
    $approvedProcessorSnapshotSource = RelatedSnapshotSource::factory()
        ->for($snapshot)
        ->create([
            'snapshot_id' => $snapshot->id,
            'snapshot_source_id' => $approvedProcessor->id,
            'snapshot_source_type' => Processor::class,
        ]);
    Snapshot::factory()
        ->for($approvedProcessor, 'snapshotSource')
        ->recycle($this->organisation)
        ->create(['state' => Approved::class]);

    // InReview
    $inReviewProcessor = Processor::factory()
        ->recycle($this->organisation)
        ->create();
    $inReviewProcessorSnapshotSource = RelatedSnapshotSource::factory()
        ->for($snapshot)
        ->create([
            'snapshot_id' => $snapshot->id,
            'snapshot_source_id' => $inReviewProcessor->id,
            'snapshot_source_type' => Processor::class,
        ]);
    Snapshot::factory()
        ->for($inReviewProcessor, 'snapshotSource')
        ->recycle($this->organisation)
        ->create(['state' => InReview::class]);

    // Obsolete
    $obsoleteProcessor = Processor::factory()
        ->recycle($this->organisation)
        ->create();
    $obsoleteProcessorSnapshotSource = RelatedSnapshotSource::factory()
        ->for($snapshot)
        ->create([
            'snapshot_id' => $snapshot->id,
            'snapshot_source_id' => $obsoleteProcessor->id,
            'snapshot_source_type' => Processor::class,
        ]);
    Snapshot::factory()
        ->for($obsoleteProcessor, 'snapshotSource')
        ->recycle($this->organisation)
        ->create(['state' => Obsolete::class]);

    livewire(RelatedSnapshotSources::class, [
        'snapshot' => $snapshot,
    ])
        ->assertCanSeeTableRecords([
            $relatedSnapshotSource,
            $deletedRelatedSnapshotSource,
            $establishedProcessorSnapshotSource,
            $approvedProcessorSnapshotSource,
            $inReviewProcessorSnapshotSource,
            $obsoleteProcessorSnapshotSource,
        ]);
});
