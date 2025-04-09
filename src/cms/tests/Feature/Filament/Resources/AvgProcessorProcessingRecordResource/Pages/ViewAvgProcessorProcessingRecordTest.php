<?php

declare(strict_types=1);

use App\Filament\Resources\AvgProcessorProcessingRecordResource;
use App\Models\Avg\AvgProcessorProcessingRecord;

it('can load the ViewAvgResponsibleProcessingRecord page', function (): void {
    $avgProcessorProcessingRecord = AvgProcessorProcessingRecord::factory()
        ->recycle($this->organisation)
        ->create();

    $this->get(AvgProcessorProcessingRecordResource::getUrl('view', ['record' => $avgProcessorProcessingRecord->id]))
        ->assertSuccessful();
});
