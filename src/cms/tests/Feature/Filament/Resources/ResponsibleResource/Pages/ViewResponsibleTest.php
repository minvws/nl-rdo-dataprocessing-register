<?php

declare(strict_types=1);

use App\Filament\Resources\ResponsibleResource;
use App\Models\Responsible;

it('can load the view page', function (): void {
    $responsible = Responsible::factory()
        ->recycle($this->organisation)
        ->create();

    $this->get(ResponsibleResource::getUrl('view', ['record' => $responsible->id]))
        ->assertSuccessful();
});
