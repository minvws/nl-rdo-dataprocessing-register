<?php

declare(strict_types=1);

use App\Filament\RelationManagers\DocumentRelationManager;
use App\Filament\Resources\AvgResponsibleProcessingRecordResource\Pages\EditAvgResponsibleProcessingRecord;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\Document;

use function Pest\Livewire\livewire;

it('loads the table', function (): void {
    $document = Document::factory()
        ->create();
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->hasAttached($document)
        ->create();

    livewire(DocumentRelationManager::class, [
        'ownerRecord' => $avgResponsibleProcessingRecord,
        'pageClass' => EditAvgResponsibleProcessingRecord::class,
    ])->assertCanSeeTableRecords([$document]);
});
