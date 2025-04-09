<?php

declare(strict_types=1);

use App\Filament\Resources\ContactPersonResource\Pages\ListContactPersons;
use App\Models\ContactPerson;

use function Pest\Livewire\livewire;

it('loads the list page', function (): void {
    $contactPersons = ContactPerson::factory()
        ->recycle($this->organisation)
        ->count(5)
        ->create();

    livewire(ListContactPersons::class)
        ->assertCanSeeTableRecords($contactPersons);
});
