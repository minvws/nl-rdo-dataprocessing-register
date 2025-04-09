<?php

declare(strict_types=1);

use App\Filament\Resources\WpgProcessingRecordServiceResource;
use App\Filament\Resources\WpgProcessingRecordServiceResource\Pages\CreateWpgProcessingRecordService;
use App\Models\Wpg\WpgProcessingRecordService;

use function Pest\Livewire\livewire;

it('loads the create page', function (): void {
    $this->get(WpgProcessingRecordServiceResource::getUrl('create'))
        ->assertSuccessful();
});

it('can create an entry', function (): void {
    $name = fake()->uuid();

    livewire(CreateWpgProcessingRecordService::class)
        ->fillForm([
            'name' => $name,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(WpgProcessingRecordService::class, [
        'name' => $name,
    ]);
});
