<?php

declare(strict_types=1);

use App\Filament\Resources\ContactPersonResource;
use App\Models\ContactPerson;

it('loads the form', function (): void {
    $contactPerson = ContactPerson::factory()
        ->recycle($this->organisation)
        ->create();

    $this->get(ContactPersonResource::getUrl('edit', ['record' => $contactPerson]))
        ->assertSuccessful();
});
