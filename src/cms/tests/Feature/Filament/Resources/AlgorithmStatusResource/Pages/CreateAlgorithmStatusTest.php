<?php

declare(strict_types=1);

use App\Filament\Resources\AlgorithmStatusResource;
use App\Filament\Resources\AlgorithmStatusResource\Pages\CreateAlgorithmStatus;
use App\Models\Algorithm\AlgorithmStatus;

it('loads the create page', function (): void {
    $this->asFilamentUser()
        ->get(AlgorithmStatusResource::getUrl('create'))
        ->assertSuccessful();
});

it('can create an entry', function (): void {
    $name = fake()->uuid();

    $this->asFilamentUser()
        ->createLivewireTestable(CreateAlgorithmStatus::class)
        ->fillForm([
            'name' => $name,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(AlgorithmStatus::class, [
        'name' => $name,
    ]);
});
