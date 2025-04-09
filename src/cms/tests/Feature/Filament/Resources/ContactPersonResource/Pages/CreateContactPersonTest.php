<?php

declare(strict_types=1);

use App\Filament\Resources\ContactPersonResource;
use App\Filament\Resources\ContactPersonResource\Pages\CreateContactPerson;
use App\Models\ContactPerson;
use App\Models\ContactPersonPosition;

use function Pest\Livewire\livewire;

it('loads the create page', function (): void {
    $this->get(ContactPersonResource::getUrl('create'))
        ->assertSuccessful();
});

it('can create an entry', function (): void {
    $contactPersonPosition = ContactPersonPosition::factory()
        ->recycle($this->organisation)
        ->create([
            'enabled' => true,
        ]);
    $name = fake()->uuid();

    livewire(CreateContactPerson::class)
        ->fillForm([
            'name' => $name,
            'contact_person_position_id' => $contactPersonPosition->id,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(ContactPerson::class, [
        'name' => $name,
    ]);
});
