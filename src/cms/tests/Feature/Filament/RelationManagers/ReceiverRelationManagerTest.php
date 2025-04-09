<?php

declare(strict_types=1);

use App\Filament\RelationManagers\ReceiverRelationManager;
use App\Filament\Resources\AvgResponsibleProcessingRecordResource\Pages\EditAvgResponsibleProcessingRecord;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\Receiver;

use function Pest\Livewire\livewire;

it('loads the table', function (): void {
    $receiver = Receiver::factory()
        ->create();
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->hasAttached($receiver)
        ->create();

    livewire(ReceiverRelationManager::class, [
        'ownerRecord' => $avgResponsibleProcessingRecord,
        'pageClass' => EditAvgResponsibleProcessingRecord::class,
    ])->assertCanSeeTableRecords([$receiver]);
});
