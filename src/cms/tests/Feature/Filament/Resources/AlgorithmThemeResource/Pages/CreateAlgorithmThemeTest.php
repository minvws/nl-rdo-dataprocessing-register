<?php

declare(strict_types=1);

use App\Filament\Resources\AlgorithmThemeResource;
use App\Filament\Resources\AlgorithmThemeResource\Pages\CreateAlgorithmTheme;
use App\Models\Algorithm\AlgorithmTheme;

use function Pest\Livewire\livewire;

it('loads the create page', function (): void {
    $this->get(AlgorithmThemeResource::getUrl('create'))
        ->assertSuccessful();
});

it('can create an entry', function (): void {
    $name = fake()->uuid();

    livewire(CreateAlgorithmTheme::class)
        ->fillForm([
            'name' => $name,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(AlgorithmTheme::class, [
        'name' => $name,
    ]);
});
