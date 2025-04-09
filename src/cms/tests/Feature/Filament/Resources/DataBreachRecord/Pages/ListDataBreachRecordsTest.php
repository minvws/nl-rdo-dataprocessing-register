<?php

declare(strict_types=1);

use App\Filament\Resources\DataBreachRecord\Pages\ListDataBreachRecords;
use App\Models\DataBreachRecord;

use function Pest\Livewire\livewire;

it('loads the list page', function (): void {
    $dataBreachRecords = DataBreachRecord::factory()
        ->recycle($this->organisation)
        ->count(5)
        ->create();

    livewire(ListDataBreachRecords::class)
        ->assertCanSeeTableRecords($dataBreachRecords);
});

it('can export', function (): void {
    DataBreachRecord::factory()
        ->recycle($this->organisation)
        ->create();

    livewire(ListDataBreachRecords::class)
        ->callAction('export')
        ->assertHasNoActionErrors()
        ->assertNotified();
});
