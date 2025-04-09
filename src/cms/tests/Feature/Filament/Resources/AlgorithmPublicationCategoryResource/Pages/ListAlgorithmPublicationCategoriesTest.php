<?php

declare(strict_types=1);

use App\Filament\Resources\AlgorithmPublicationCategoryResource\Pages\ListAlgorithmPublicationCategories;
use App\Models\Algorithm\AlgorithmPublicationCategory;

use function Pest\Livewire\livewire;

it('can load the list resource page', function (): void {
    $records = AlgorithmPublicationCategory::factory()
        ->recycle($this->organisation)
        ->count(5)
        ->create();

    livewire(ListAlgorithmPublicationCategories::class)
        ->assertCanSeeTableRecords($records);
});
