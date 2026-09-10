<?php

declare(strict_types=1);

use App\Filament\RelationManagers\SnapshotsRelationManager;
use App\Filament\Resources\AvgResponsibleProcessingRecordResource\Pages\EditAvgResponsibleProcessingRecord;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\Snapshot;
use Tests\Helpers\Model\OrganisationTestHelper;

it('loads the table', function (): void {
    $organisation = OrganisationTestHelper::create();
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
        ->create();
    $snapshot = Snapshot::factory()
        ->recycle($organisation)
        ->for($avgResponsibleProcessingRecord, 'snapshotSource')
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(SnapshotsRelationManager::class, [
            'ownerRecord' => $avgResponsibleProcessingRecord,
            'pageClass' => EditAvgResponsibleProcessingRecord::class,
        ])
        ->assertCanSeeTableRecords([$snapshot]);
});

it('reloads the snapshots-table', function (): void {
    $organisation = OrganisationTestHelper::create();
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
        ->create();

    $snapshotRelationManager = $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(SnapshotsRelationManager::class, [
            'ownerRecord' => $avgResponsibleProcessingRecord,
            'pageClass' => EditAvgResponsibleProcessingRecord::class,
        ])
        ->assertCanSeeTableRecords([]);

    $snapshot = Snapshot::factory()->make();
    $avgResponsibleProcessingRecord->snapshots()->save($snapshot);

    $snapshotRelationManager->fireEvent(SnapshotsRelationManager::REFRESH_TABLE_EVENT)
        ->assertCanSeeTableRecords([$snapshot]);
});
