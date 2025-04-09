<?php

declare(strict_types=1);

use App\Filament\Resources\AlgorithmRecordResource;
use App\Models\Algorithm\AlgorithmRecord;

it('can load the ViewAlgorithmRecord page', function (): void {
    $algorithmRecord = AlgorithmRecord::factory()
        ->recycle($this->organisation)
        ->create();

    $this->get(AlgorithmRecordResource::getUrl('view', ['record' => $algorithmRecord->id]))
        ->assertSuccessful();
});
