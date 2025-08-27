<?php

declare(strict_types=1);

use App\Filament\Resources\AvgProcessorProcessingRecordServiceResource;
use App\Filament\Resources\AvgProcessorProcessingRecordServiceResource\Pages\CreateAvgProcessorProcessingRecordService;
use App\Models\Avg\AvgProcessorProcessingRecordService;

it('loads the create page', function (): void {
    $this->asFilamentUser()
        ->get(AvgProcessorProcessingRecordServiceResource::getUrl('create'))
        ->assertSuccessful();
});

it('can create an entry', function (): void {
    $name = fake()->uuid();

    $this->asFilamentUser()
        ->createLivewireTestable(CreateAvgProcessorProcessingRecordService::class)
        ->fillForm([
            'name' => $name,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(AvgProcessorProcessingRecordService::class, [
        'name' => $name,
    ]);
});
