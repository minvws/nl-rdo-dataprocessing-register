<?php

declare(strict_types=1);

use App\Filament\Resources\ResponsibleLegalEntityResource;
use App\Filament\Resources\ResponsibleLegalEntityResource\Pages\CreateResponsibleLegalEntity;
use App\Models\ResponsibleLegalEntity;

it('loads the create page', function (): void {
    $this->asFilamentUser()
        ->get(ResponsibleLegalEntityResource::getUrl('create'))
        ->assertSuccessful();
});

it('can create an entry', function (): void {
    $name = fake()->uuid();

    $this->asFilamentUser()
        ->createLivewireTestable(CreateResponsibleLegalEntity::class)
        ->fillForm([
            'name' => $name,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(ResponsibleLegalEntity::class, [
        'name' => $name,
    ]);
});
