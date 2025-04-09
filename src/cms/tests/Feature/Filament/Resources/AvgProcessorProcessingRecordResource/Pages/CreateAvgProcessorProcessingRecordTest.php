<?php

declare(strict_types=1);

use App\Enums\CoreEntityDataCollectionSource;
use App\Filament\Resources\AvgProcessorProcessingRecordResource;
use App\Filament\Resources\AvgProcessorProcessingRecordResource\Pages\CreateAvgProcessorProcessingRecord;
use App\Models\Avg\AvgProcessorProcessingRecord;
use App\Models\Avg\AvgProcessorProcessingRecordService;
use App\Models\Responsible;

use function Pest\Livewire\livewire;

it('loads the create page', function (): void {
    $this->get(AvgProcessorProcessingRecordResource::getUrl('create'))
        ->assertSuccessful();
});

it('can create an entry', function (): void {
    $avgProcessorProcessingRecordService = AvgProcessorProcessingRecordService::factory()
        ->recycle($this->organisation)
        ->create([
            'enabled' => true,
        ]);
    $responsible = Responsible::factory()
        ->recycle($this->organisation)
        ->create();
    $name = fake()->uuid();

    livewire(CreateAvgProcessorProcessingRecord::class)
        ->fillForm([
            'data_collection_source' => CoreEntityDataCollectionSource::PRIMARY->value,
            'name' => $name,
            'avg_processor_processing_record_service_id' => $avgProcessorProcessingRecordService->id,
            'responsible_id' => [$responsible->id],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(AvgProcessorProcessingRecord::class, [
        'name' => $name,
    ]);
});

it('does not allow an invalid value for dataCollectionSource', function (): void {
    $avgProcessorProcessingRecordService = AvgProcessorProcessingRecordService::factory()
        ->recycle($this->organisation)
        ->create([
            'enabled' => true,
        ]);
    $name = fake()->uuid();

    livewire(CreateAvgProcessorProcessingRecord::class)
        ->fillForm([
            'data_collection_source' => 'invalid',
            'name' => $name,
            'avg_processor_processing_record_service_id' => $avgProcessorProcessingRecordService->id,
        ])
        ->call('create')
        ->assertHasFormErrors(['data_collection_source']);
});
