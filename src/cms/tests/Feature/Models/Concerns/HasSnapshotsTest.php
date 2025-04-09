<?php

declare(strict_types=1);

use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\ContactPerson;
use App\Models\Processor;
use App\Models\Receiver;
use App\Models\Responsible;
use App\Models\System;

it('can load the related snapshot sources', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->create();
    $relatedSnapshotSources = $avgResponsibleProcessingRecord->getRelatedSnapshotSources();
    expect($relatedSnapshotSources->keys()->toArray())
        ->toBe([
            ContactPerson::class,
            Processor::class,
            Receiver::class,
            Responsible::class,
            System::class,
        ]);
});
