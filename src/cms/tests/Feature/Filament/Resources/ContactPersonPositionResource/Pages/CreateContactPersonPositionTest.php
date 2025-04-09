<?php

declare(strict_types=1);

use App\Filament\Resources\ContactPersonPositionResource;
use App\Filament\Resources\ContactPersonPositionResource\Pages\CreateContactPersonPosition;
use App\Models\ContactPersonPosition;

use function Pest\Livewire\livewire;

it('loads the create page', function (): void {
    $this->get(ContactPersonPositionResource::getUrl('create'))
        ->assertSuccessful();
});

it('can create an entry', function (): void {
    $name = fake()->uuid();

    livewire(CreateContactPersonPosition::class)
        ->fillForm([
            'name' => $name,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(ContactPersonPosition::class, [
        'name' => $name,
    ]);
});
