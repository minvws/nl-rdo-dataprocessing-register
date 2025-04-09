<?php

declare(strict_types=1);

use App\Filament\Resources\AlgorithmRecordResource;
use App\Filament\Resources\AlgorithmRecordResource\Pages\CreateAlgorithmRecord;
use App\Models\Algorithm\AlgorithmMetaSchema;
use App\Models\Algorithm\AlgorithmPublicationCategory;
use App\Models\Algorithm\AlgorithmRecord;
use App\Models\Algorithm\AlgorithmStatus;
use App\Models\Algorithm\AlgorithmTheme;

use function Pest\Livewire\livewire;

it('loads the create page', function (): void {
    $this->get(AlgorithmRecordResource::getUrl('create'))
        ->assertSuccessful();
});

it('can create an entry', function (): void {
    $algorithmMetaSchema = AlgorithmMetaSchema::factory()
        ->recycle($this->organisation)
        ->create(['enabled' => true]);
    $algorithmPublicationCategory = AlgorithmPublicationCategory::factory()
        ->recycle($this->organisation)
        ->create(['enabled' => true]);
    $algorithmStatus = AlgorithmStatus::factory()
        ->recycle($this->organisation)
        ->create(['enabled' => true]);
    $algorithmTheme = AlgorithmTheme::factory()
        ->recycle($this->organisation)
        ->create(['enabled' => true]);
    $name = fake()->uuid();

    livewire(CreateAlgorithmRecord::class)
        ->fillForm([
            'name' => $name,
            'algorithm_meta_schema_id' => $algorithmMetaSchema->id,
            'algorithm_publication_category_id' => $algorithmPublicationCategory->id,
            'algorithm_status_id' => $algorithmStatus->id,
            'algorithm_theme_id' => $algorithmTheme->id,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(AlgorithmRecord::class, [
        'name' => $name,
    ]);
});
