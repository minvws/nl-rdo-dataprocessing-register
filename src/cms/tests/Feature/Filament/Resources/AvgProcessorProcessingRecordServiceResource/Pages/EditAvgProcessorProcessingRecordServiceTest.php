<?php

declare(strict_types=1);

use App\Filament\Resources\AvgProcessorProcessingRecordServiceResource;
use App\Models\Avg\AvgProcessorProcessingRecordService;

it('loads the edit page', function (): void {
    $avgProcessorProcessingRecordService = AvgProcessorProcessingRecordService::factory()
        ->recycle($this->organisation)
        ->create();

    $this->get(
        AvgProcessorProcessingRecordServiceResource::getUrl('edit', ['record' => $avgProcessorProcessingRecordService->id]),
    )->assertSuccessful();
});
