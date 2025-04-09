<?php

declare(strict_types=1);

use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\Stakeholder;

it('has avg responsible processing records', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->create();
    $processor = Stakeholder::factory()
        ->hasAttached($avgResponsibleProcessingRecord)
        ->create();

    expect($processor->avgResponsibleProcessingRecords()->first()->id)
        ->toBe($avgResponsibleProcessingRecord->id);
});
