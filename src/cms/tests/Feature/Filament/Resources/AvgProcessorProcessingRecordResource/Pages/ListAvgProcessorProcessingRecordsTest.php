<?php

declare(strict_types=1);

use App\Filament\Resources\AvgProcessorProcessingRecordResource\Pages\ListAvgProcessorProcessingRecords;
use App\Models\Avg\AvgProcessorProcessingRecord;

use function Pest\Livewire\livewire;

it('loads the list page', function (): void {
    $avgProcessorProcessingRecords = AvgProcessorProcessingRecord::factory()
        ->recycle($this->organisation)
        ->count(5)
        ->create();

    livewire(ListAvgProcessorProcessingRecords::class)
        ->assertCanSeeTableRecords($avgProcessorProcessingRecords);
});

it('can export', function (): void {
    AvgProcessorProcessingRecord::factory()
        ->recycle($this->organisation)
        ->create();

    AvgProcessorProcessingRecord::factory()
        ->recycle($this->organisation)
        ->withSnapshots(1)
        ->create();

    livewire(ListAvgProcessorProcessingRecords::class)
        ->callAction('export')
        ->assertHasNoActionErrors()
        ->assertNotified();
});
