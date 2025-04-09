<?php

declare(strict_types=1);

use App\Filament\Resources\SystemResource;
use App\Models\System;

it('can load the ViewSystem page', function (): void {
    $system = System::factory()
        ->recycle($this->organisation)
        ->create();

    $this->get(SystemResource::getUrl('view', ['record' => $system->id]))
        ->assertSuccessful();
});
