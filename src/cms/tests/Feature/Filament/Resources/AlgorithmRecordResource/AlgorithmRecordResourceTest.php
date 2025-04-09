<?php

declare(strict_types=1);

namespace Tests\Feature\Filament\Resources\AlgorithmRecordResource;

use App\Filament\Resources\AlgorithmRecordResource;
use App\Filament\Resources\AlgorithmRecordResource\Pages\ListAlgorithmRecords;
use App\Models\Algorithm\AlgorithmRecord;

use function it;
use function Pest\Livewire\livewire;

it('loads the form', function (): void {
    $algorithmRecord = AlgorithmRecord::factory()
        ->recycle($this->organisation)
        ->create();

    $this->get(AlgorithmRecordResource::getUrl('edit', ['record' => $algorithmRecord]))
        ->assertSuccessful();
});

it('loads the list page', function (): void {
    $algorithmRecord = AlgorithmRecord::factory()
        ->recycle($this->organisation)
        ->create();

    livewire(ListAlgorithmRecords::class)
        ->assertCanSeeTableRecords([$algorithmRecord]);
});

it('loads the create page', function (): void {
    $this->get(AlgorithmRecordResource::getUrl('create'))
        ->assertSuccessful();
});
