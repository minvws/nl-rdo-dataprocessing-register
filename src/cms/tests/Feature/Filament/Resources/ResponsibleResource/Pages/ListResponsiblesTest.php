<?php

declare(strict_types=1);

use App\Filament\Resources\ResponsibleResource\Pages\ListResponsibles;
use App\Models\Responsible;

use function Pest\Livewire\livewire;

it('loads the list page', function (): void {
    $responsibles = Responsible::factory()
        ->recycle($this->organisation)
        ->count(5)
        ->create();

    livewire(ListResponsibles::class)
        ->assertCanSeeTableRecords($responsibles);
});
