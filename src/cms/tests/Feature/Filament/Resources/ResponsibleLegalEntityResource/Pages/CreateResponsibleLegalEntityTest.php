<?php

declare(strict_types=1);

use App\Enums\Authorization\Role;
use App\Filament\Resources\ResponsibleLegalEntityResource;
use App\Filament\Resources\ResponsibleLegalEntityResource\Pages\CreateResponsibleLegalEntity;
use App\Models\ResponsibleLegalEntity;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    $this->user->assignGlobalRole(Role::FUNCTIONAL_MANAGER);
});

it('loads the create page', function (): void {
    $this->get(ResponsibleLegalEntityResource::getUrl('create'))
        ->assertSuccessful();
});

it('can create an entry', function (): void {
    $name = fake()->uuid();

    livewire(CreateResponsibleLegalEntity::class)
        ->fillForm([
            'name' => $name,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(ResponsibleLegalEntity::class, [
        'name' => $name,
    ]);
});
