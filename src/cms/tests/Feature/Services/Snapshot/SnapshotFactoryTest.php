<?php

declare(strict_types=1);

use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Services\Snapshot\SnapshotFactory;

it('saves the snapshot', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()->create();

    expect($avgResponsibleProcessingRecord->snapshots()->count())
        ->toBe(0);

    /** @var SnapshotFactory $snapshotFactory */
    $snapshotFactory = $this->app->get(SnapshotFactory::class);
    $snapshotFactory->fromSnapshotSource($avgResponsibleProcessingRecord);

    expect($avgResponsibleProcessingRecord->snapshots()->count())
        ->toBe(1);
});
