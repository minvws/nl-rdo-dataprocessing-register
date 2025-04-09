<?php

declare(strict_types=1);

use App\Filament\Resources\AlgorithmThemeResource;
use App\Models\Algorithm\AlgorithmTheme;

it('can load the view page', function (): void {
    $algorithmTheme = AlgorithmTheme::factory()
        ->recycle($this->organisation)
        ->create();

    $this->get(AlgorithmThemeResource::getUrl('view', ['record' => $algorithmTheme->id]))
        ->assertSuccessful();
});
