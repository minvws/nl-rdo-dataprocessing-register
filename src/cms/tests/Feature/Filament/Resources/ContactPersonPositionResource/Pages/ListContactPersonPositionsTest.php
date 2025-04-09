<?php

declare(strict_types=1);

use App\Filament\Resources\ContactPersonPositionResource\Pages\ListContactPersonPositions;
use App\Models\ContactPersonPosition;

use function Pest\Livewire\livewire;

it('loads the list page', function (): void {
    $contactPersonPositions = ContactPersonPosition::factory()
        ->recycle($this->organisation)
        ->count(5)
        ->create();

    livewire(ListContactPersonPositions::class)
        ->assertCanSeeTableRecords($contactPersonPositions);
});
