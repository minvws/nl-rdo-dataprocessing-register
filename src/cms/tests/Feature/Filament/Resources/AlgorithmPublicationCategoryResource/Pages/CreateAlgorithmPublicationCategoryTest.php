<?php

declare(strict_types=1);

use App\Filament\Resources\AlgorithmPublicationCategoryResource;
use App\Filament\Resources\AlgorithmPublicationCategoryResource\Pages\CreateAlgorithmPublicationCategory;
use App\Models\Algorithm\AlgorithmPublicationCategory;

use function Pest\Livewire\livewire;

it('loads the create page', function (): void {
    $this->get(AlgorithmPublicationCategoryResource::getUrl('create'))
        ->assertSuccessful();
});

it('can create an entry', function (): void {
    $name = fake()->uuid();

    livewire(CreateAlgorithmPublicationCategory::class)
        ->fillForm([
            'name' => $name,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(AlgorithmPublicationCategory::class, [
        'name' => $name,
    ]);
});
