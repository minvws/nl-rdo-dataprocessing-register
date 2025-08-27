<?php

declare(strict_types=1);

use App\Filament\Resources\AvgResponsibleProcessingRecordServiceResource;
use App\Filament\Resources\AvgResponsibleProcessingRecordServiceResource\Pages\CreateAvgResponsibleProcessingRecordService;
use App\Models\Avg\AvgResponsibleProcessingRecordService;

it('loads the create page', function (): void {
    $this->asFilamentUser()
        ->get(AvgResponsibleProcessingRecordServiceResource::getUrl('create'))
        ->assertSuccessful();
});

it('can create an entry', function (): void {
    $name = fake()->uuid();

    $this->asFilamentUser()
        ->createLivewireTestable(CreateAvgResponsibleProcessingRecordService::class)
        ->fillForm([
            'name' => $name,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(AvgResponsibleProcessingRecordService::class, [
        'name' => $name,
    ]);
});
