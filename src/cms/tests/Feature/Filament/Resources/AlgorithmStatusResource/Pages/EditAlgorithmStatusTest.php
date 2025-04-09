<?php

declare(strict_types=1);

use App\Filament\Resources\AlgorithmStatusResource;
use App\Models\Algorithm\AlgorithmStatus;

it('can load the edit page', function (): void {
    $algorithmStatus = AlgorithmStatus::factory()
        ->recycle($this->organisation)
        ->create();

    $this->get(AlgorithmStatusResource::getUrl('edit', ['record' => $algorithmStatus->id]))
        ->assertSuccessful();
});
