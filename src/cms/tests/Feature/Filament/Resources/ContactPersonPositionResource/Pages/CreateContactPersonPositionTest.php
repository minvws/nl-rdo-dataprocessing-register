<?php

declare(strict_types=1);

use App\Filament\Resources\ContactPersonPositionResource;
use App\Filament\Resources\ContactPersonPositionResource\Pages\CreateContactPersonPosition;
use App\Models\ContactPersonPosition;

it('loads the create page', function (): void {
    $this->asFilamentUser()
        ->get(ContactPersonPositionResource::getUrl('create'))
        ->assertSuccessful();
});

it('can create an entry', function (): void {
    $name = fake()->uuid();

    $this->asFilamentUser()
        ->createLivewireTestable(CreateContactPersonPosition::class)
        ->fillForm([
            'name' => $name,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(ContactPersonPosition::class, [
        'name' => $name,
    ]);
});
