<?php

declare(strict_types=1);

use App\Filament\Resources\AvgResponsibleProcessingRecordServiceResource;
use App\Filament\Resources\AvgResponsibleProcessingRecordServiceResource\Pages\CreateAvgResponsibleProcessingRecordService;
use App\Models\Avg\AvgResponsibleProcessingRecordService;

use function Pest\Livewire\livewire;

it('loads the create page', function (): void {
    $this->get(AvgResponsibleProcessingRecordServiceResource::getUrl('create'))
        ->assertSuccessful();
});

it('can create an entry', function (): void {
    $name = fake()->uuid();

    livewire(CreateAvgResponsibleProcessingRecordService::class)
        ->fillForm([
            'name' => $name,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(AvgResponsibleProcessingRecordService::class, [
        'name' => $name,
    ]);
});
