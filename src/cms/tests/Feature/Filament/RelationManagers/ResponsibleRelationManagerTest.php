<?php

declare(strict_types=1);

use App\Filament\RelationManagers\ResponsibleRelationManager;
use App\Filament\Resources\AvgResponsibleProcessingRecordResource\Pages\EditAvgResponsibleProcessingRecord;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\Responsible;

it('loads the table', function (): void {
    $responsible = Responsible::factory()
        ->create();
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->hasAttached($responsible)
        ->create();

    $this->asFilamentUser()
        ->createLivewireTestable(ResponsibleRelationManager::class, [
            'ownerRecord' => $avgResponsibleProcessingRecord,
            'pageClass' => EditAvgResponsibleProcessingRecord::class,
        ])
        ->assertCanSeeTableRecords([$responsible]);
});
