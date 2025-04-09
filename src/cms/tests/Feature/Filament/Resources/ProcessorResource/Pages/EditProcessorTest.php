<?php

declare(strict_types=1);

use App\Filament\Resources\ProcessorResource;
use App\Models\Processor;

it('loads the edit page', function (): void {
    $processor = Processor::factory()
        ->recycle($this->organisation)
        ->create();

    $this->get(ProcessorResource::getUrl('edit', ['record' => $processor->id]))
        ->assertSuccessful();
});
