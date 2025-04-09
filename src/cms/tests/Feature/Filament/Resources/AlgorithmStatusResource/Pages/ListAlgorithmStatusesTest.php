<?php

declare(strict_types=1);

use App\Filament\Resources\AlgorithmStatusResource\Pages\ListAlgorithmStatuses;
use App\Models\Algorithm\AlgorithmStatus;

use function Pest\Livewire\livewire;

it('can load the list resource page', function (): void {
    $records = AlgorithmStatus::factory()
        ->recycle($this->organisation)
        ->count(5)
        ->create();

    livewire(ListAlgorithmStatuses::class)
        ->assertCanSeeTableRecords($records);
});
