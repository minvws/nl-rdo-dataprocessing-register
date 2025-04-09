<?php

declare(strict_types=1);

use App\Filament\RelationManagers\AvgProcessorProcessingRecordRelationManager;
use App\Filament\Resources\DocumentResource\Pages\EditDocument;
use App\Models\Document;

use function Pest\Livewire\livewire;

it('loads the table', function (): void {
    $document = Document::factory()
        ->withAvgProcessorProcessingRecords()
        ->create();
    $document->refresh();

    livewire(AvgProcessorProcessingRecordRelationManager::class, [
        'ownerRecord' => $document,
        'pageClass' => EditDocument::class,
    ])->assertCanSeeTableRecords($document->avgProcessorProcessingRecords);
});
