<?php

declare(strict_types=1);

use App\Filament\Resources\AlgorithmStatusResource;
use App\Models\Algorithm\AlgorithmStatus;

it('can load the view page', function (): void {
    $algorithmStatus = AlgorithmStatus::factory()
        ->recycle($this->organisation)
        ->create();

    $this->get(AlgorithmStatusResource::getUrl('view', ['record' => $algorithmStatus->id]))
        ->assertSuccessful();
});
