<?php

declare(strict_types=1);

use App\Filament\Resources\AlgorithmMetaSchemaResource\Pages\ListAlgorithmMetaSchemas;
use App\Models\Algorithm\AlgorithmMetaSchema;

use function Pest\Livewire\livewire;

it('can load the list resource page', function (): void {
    $records = AlgorithmMetaSchema::factory()
        ->recycle($this->organisation)
        ->count(5)
        ->create();

    livewire(ListAlgorithmMetaSchemas::class)
        ->assertCanSeeTableRecords($records);
});
