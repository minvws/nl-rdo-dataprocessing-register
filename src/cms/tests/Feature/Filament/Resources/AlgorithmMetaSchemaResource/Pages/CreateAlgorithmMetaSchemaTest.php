<?php

declare(strict_types=1);

use App\Filament\Resources\AlgorithmMetaSchemaResource;
use App\Filament\Resources\AlgorithmMetaSchemaResource\Pages\CreateAlgorithmMetaSchema;
use App\Models\Algorithm\AlgorithmMetaSchema;

it('loads the create page', function (): void {
    $this->asFilamentUser()
        ->get(AlgorithmMetaSchemaResource::getUrl('create'))
        ->assertSuccessful();
});

it('can create an entry', function (): void {
    $name = fake()->uuid();

    $this->asFilamentUser()
        ->createLivewireTestable(CreateAlgorithmMetaSchema::class)
        ->fillForm([
            'name' => $name,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(AlgorithmMetaSchema::class, [
        'name' => $name,
    ]);
});
