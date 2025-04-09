<?php

declare(strict_types=1);

use App\Filament\Resources\ResponsibleResource;
use App\Models\Responsible;

it('loads the edit page', function (): void {
    $responsible = Responsible::factory()
        ->recycle($this->organisation)
        ->create();

    $this->get(ResponsibleResource::getUrl('edit', ['record' => $responsible->id]))
        ->assertSuccessful();
});
