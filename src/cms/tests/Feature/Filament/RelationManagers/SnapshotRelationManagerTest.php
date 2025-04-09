<?php

declare(strict_types=1);

use App\Filament\RelationManagers\SnapshotsRelationManager;
use App\Filament\Resources\AvgResponsibleProcessingRecordResource\Pages\EditAvgResponsibleProcessingRecord;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\Snapshot;

use function Pest\Livewire\livewire;

it('loads the table', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->create();
    $snapshot = Snapshot::factory()
        ->for($avgResponsibleProcessingRecord, 'snapshotSource')
        ->create();

    livewire(SnapshotsRelationManager::class, [
        'ownerRecord' => $avgResponsibleProcessingRecord,
        'pageClass' => EditAvgResponsibleProcessingRecord::class,
    ])->assertCanSeeTableRecords([$snapshot]);
});

it('reloads the snapshots-table', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->create();

    $snapshotRelationManager = livewire(SnapshotsRelationManager::class, [
        'ownerRecord' => $avgResponsibleProcessingRecord,
        'pageClass' => EditAvgResponsibleProcessingRecord::class,
    ])->assertCanSeeTableRecords([]);

    $snapshot = Snapshot::factory()->make();
    $avgResponsibleProcessingRecord->snapshots()->save($snapshot);

    $snapshotRelationManager->fireEvent(SnapshotsRelationManager::REFRESH_TABLE_EVENT)
        ->assertCanSeeTableRecords([$snapshot]);
});
