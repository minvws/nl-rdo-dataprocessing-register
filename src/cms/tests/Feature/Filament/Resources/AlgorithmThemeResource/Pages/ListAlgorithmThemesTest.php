<?php

declare(strict_types=1);

use App\Filament\Resources\AlgorithmThemeResource\Pages\ListAlgorithmThemes;
use App\Models\Algorithm\AlgorithmTheme;

use function Pest\Livewire\livewire;

it('can load the list resource page', function (): void {
    $records = AlgorithmTheme::factory()
        ->recycle($this->organisation)
        ->count(5)
        ->create();

    livewire(ListAlgorithmThemes::class)
        ->assertCanSeeTableRecords($records);
});
