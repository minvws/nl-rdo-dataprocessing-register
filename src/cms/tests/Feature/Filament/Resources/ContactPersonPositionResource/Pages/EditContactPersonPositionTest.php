<?php

declare(strict_types=1);

use App\Filament\Resources\ContactPersonPositionResource;
use App\Models\ContactPersonPosition;

it('loads the edit page', function (): void {
    $contactPersonPosition = ContactPersonPosition::factory()
        ->recycle($this->organisation)
        ->create();

    $this->get(ContactPersonPositionResource::getUrl('edit', ['record' => $contactPersonPosition->id]))
        ->assertSuccessful();
});
