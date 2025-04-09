<?php

declare(strict_types=1);

use App\Filament\Resources\AlgorithmStatusResource;
use App\Filament\Resources\AlgorithmStatusResource\Pages\CreateAlgorithmStatus;
use App\Models\Algorithm\AlgorithmStatus;

use function Pest\Livewire\livewire;

it('loads the create page', function (): void {
    $this->get(AlgorithmStatusResource::getUrl('create'))
        ->assertSuccessful();
});

it('can create an entry', function (): void {
    $name = fake()->uuid();

    livewire(CreateAlgorithmStatus::class)
        ->fillForm([
            'name' => $name,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(AlgorithmStatus::class, [
        'name' => $name,
    ]);
});
