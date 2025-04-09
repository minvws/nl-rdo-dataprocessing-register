<?php

declare(strict_types=1);

use App\Filament\Resources\ProcessorResource\Pages\ListProcessors;
use App\Models\Processor;

use function Pest\Livewire\livewire;

it('loads the list page', function (): void {
    $processors = Processor::factory()
        ->recycle($this->organisation)
        ->count(5)
        ->create();

    livewire(ListProcessors::class)
        ->assertCanSeeTableRecords($processors);
});
