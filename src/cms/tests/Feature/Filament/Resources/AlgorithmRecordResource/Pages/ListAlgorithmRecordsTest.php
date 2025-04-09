<?php

declare(strict_types=1);

use App\Filament\Resources\AlgorithmRecordResource\Pages\ListAlgorithmRecords;
use App\Models\Algorithm\AlgorithmRecord;

use function Pest\Livewire\livewire;

it('loads the list page', function (): void {
    $algorithmRecords = AlgorithmRecord::factory()
        ->recycle($this->organisation)
        ->count(5)
        ->create();

    livewire(ListAlgorithmRecords::class)
        ->assertCanSeeTableRecords($algorithmRecords);
});

it('can export', function (): void {
    AlgorithmRecord::factory()
        ->recycle($this->organisation)
        ->create();

    AlgorithmRecord::factory()
        ->recycle($this->organisation)
        ->withSnapshots(1)
        ->create();

    livewire(ListAlgorithmRecords::class)
        ->callAction('export')
        ->assertHasNoActionErrors()
        ->assertNotified();
});
