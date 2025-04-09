<?php

declare(strict_types=1);

use App\Filament\Resources\SystemResource\Pages\ListSystems;
use App\Models\System;

use function Pest\Livewire\livewire;

it('loads the list page', function (): void {
    $systems = System::factory()
        ->recycle($this->organisation)
        ->count(5)
        ->create();

    livewire(ListSystems::class)
        ->assertCanSeeTableRecords($systems);
});
