<?php

declare(strict_types=1);

use App\Filament\Resources\AvgResponsibleProcessingRecordServiceResource;
use App\Models\Avg\AvgResponsibleProcessingRecordService;

it('loads the view page', function (): void {
    $avgResponsibleProcessingRecordService = AvgResponsibleProcessingRecordService::factory()
        ->recycle($this->organisation)
        ->create();

    $this->get(
        AvgResponsibleProcessingRecordServiceResource::getUrl('view', ['record' => $avgResponsibleProcessingRecordService->id]),
    )->assertSuccessful();
});
