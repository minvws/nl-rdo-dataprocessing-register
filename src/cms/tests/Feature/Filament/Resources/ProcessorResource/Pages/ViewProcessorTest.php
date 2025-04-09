<?php

declare(strict_types=1);

use App\Filament\Resources\ProcessorResource;
use App\Models\Processor;

it('can load the ViewProcessor page', function (): void {
    $processor = Processor::factory()
        ->recycle($this->organisation)
        ->create();

    $this->get(ProcessorResource::getUrl('view', ['record' => $processor->id]))
        ->assertSuccessful();
});
