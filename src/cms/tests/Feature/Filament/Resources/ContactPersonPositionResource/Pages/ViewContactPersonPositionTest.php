<?php

declare(strict_types=1);

use App\Filament\Resources\ContactPersonPositionResource;
use App\Models\ContactPersonPosition;

it('loads the view page', function (): void {
    $contactPersonPosition = ContactPersonPosition::factory()
        ->recycle($this->organisation)
        ->create();

    $this->get(ContactPersonPositionResource::getUrl('view', ['record' => $contactPersonPosition->id]))
        ->assertSuccessful();
});
