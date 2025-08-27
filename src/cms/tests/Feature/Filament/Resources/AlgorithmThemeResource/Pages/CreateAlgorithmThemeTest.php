<?php

declare(strict_types=1);

use App\Filament\Resources\AlgorithmThemeResource;
use App\Filament\Resources\AlgorithmThemeResource\Pages\CreateAlgorithmTheme;
use App\Models\Algorithm\AlgorithmTheme;

it('loads the create page', function (): void {
    $this->asFilamentUser()
        ->get(AlgorithmThemeResource::getUrl('create'))
        ->assertSuccessful();
});

it('can create an entry', function (): void {
    $name = fake()->uuid();

    $this->asFilamentUser()
        ->createLivewireTestable(CreateAlgorithmTheme::class)
        ->fillForm([
            'name' => $name,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(AlgorithmTheme::class, [
        'name' => $name,
    ]);
});
