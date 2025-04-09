<?php

declare(strict_types=1);

use App\Livewire\Snapshot\RelatedSnapshotSourcesValidation;
use App\Models\Processor;
use App\Models\RelatedSnapshotSource;
use App\Models\Snapshot;
use App\Models\States\Snapshot\Approved;

use function Pest\Livewire\livewire;

it('can load the table', function (): void {
    $processor = Processor::factory()->create();
    $snapshot = Snapshot::factory()
        ->recycle($this->organisation)
        ->create([
            'state' => Approved::class,
            'snapshot_source_id' => $processor->id,
            'snapshot_source_type' => $processor::class,
        ]);
    RelatedSnapshotSource::factory()
        ->for($snapshot)
        ->create([
            'snapshot_source_id' => $processor->id,
        ]);

    livewire(RelatedSnapshotSourcesValidation::class, [
        'snapshot' => $snapshot,
    ])
        ->loadTable()
        ->assertCountTableRecords(1);
});
