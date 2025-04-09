<?php

declare(strict_types=1);

use App\Filament\RelationManagers\SystemRelationManager;
use App\Filament\Resources\AvgResponsibleProcessingRecordResource\Pages\EditAvgResponsibleProcessingRecord;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\System;

use function Pest\Livewire\livewire;

it('loads the table', function (): void {
    $system = System::factory()
        ->create();
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->hasAttached($system)
        ->create();

    livewire(SystemRelationManager::class, [
        'ownerRecord' => $avgResponsibleProcessingRecord,
        'pageClass' => EditAvgResponsibleProcessingRecord::class,
    ])->assertCanSeeTableRecords([$system]);
});
