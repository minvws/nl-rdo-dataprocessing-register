<?php

declare(strict_types=1);

use App\Filament\Resources\ResponsibleResource;
use App\Filament\Resources\ResponsibleResource\Pages\CreateResponsible;
use App\Models\Responsible;

use function Pest\Livewire\livewire;

it('loads the create page', function (): void {
    $this->get(ResponsibleResource::getUrl('create'))
        ->assertSuccessful();
});

it('can create an entry', function (): void {
    $name = fake()->uuid();

    livewire(CreateResponsible::class)
        ->fillForm([
            'name' => $name,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Responsible::class, [
        'name' => $name,
    ]);
});
