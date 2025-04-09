<?php

declare(strict_types=1);

use App\Filament\Resources\ContactPersonResource;
use App\Models\ContactPerson;

it('loads the view page', function (): void {
    $contactPerson = ContactPerson::factory()
        ->recycle($this->organisation)
        ->create();

    $this->get(ContactPersonResource::getUrl('view', ['record' => $contactPerson]))
        ->assertSuccessful();
});
