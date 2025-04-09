<?php

declare(strict_types=1);

use App\Enums\CoreEntityDataCollectionSource;
use App\Filament\Resources\WpgProcessingRecordResource;
use App\Filament\Resources\WpgProcessingRecordResource\Pages\CreateWpgProcessingRecord;
use App\Models\Wpg\WpgProcessingRecord;
use App\Models\Wpg\WpgProcessingRecordService;

use function Pest\Livewire\livewire;

it('loads the create page', function (): void {
    $this->get(WpgProcessingRecordResource::getUrl('create'))
        ->assertSuccessful();
});

it('can create an entry', function (): void {
    $wpgProcessingRecordService = WpgProcessingRecordService::factory()
        ->recycle($this->organisation)
        ->create([
            'enabled' => true,
        ]);
    $name = fake()->uuid();

    livewire(CreateWpgProcessingRecord::class)
        ->fillForm([
            'data_collection_source' => CoreEntityDataCollectionSource::PRIMARY->value,
            'name' => $name,
            'wpg_processing_record_service_id' => $wpgProcessingRecordService->id,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(WpgProcessingRecord::class, [
        'name' => $name,
    ]);
});
