<?php

declare(strict_types=1);

use App\Filament\RelationManagers\ProcessorRelationManager;
use App\Filament\Resources\AvgResponsibleProcessingRecordResource\Pages\EditAvgResponsibleProcessingRecord;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\Processor;

use function Pest\Livewire\livewire;

it('loads the table', function (): void {
    $processor = Processor::factory()
        ->create();
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->hasAttached($processor)
        ->create();

    livewire(ProcessorRelationManager::class, [
        'ownerRecord' => $avgResponsibleProcessingRecord,
        'pageClass' => EditAvgResponsibleProcessingRecord::class,
    ])->assertCanSeeTableRecords([$processor]);
});
