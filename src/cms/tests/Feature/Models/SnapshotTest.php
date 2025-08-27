<?php

declare(strict_types=1);

use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\Organisation;
use App\Models\Snapshot;

it('belongs to an organisation', function (): void {
    $snapshot = Snapshot::factory()->create();

    expect($snapshot->organisation)
        ->toBeInstanceOf(Organisation::class);
});

it('can hold all snapshot states', function ($snapshotState): void {
    $snapshot = Snapshot::factory()->create([
        'state' => $snapshotState,
    ]);

    expect((string) $snapshot->state)
        ->toBe($snapshotState);
})->with(Snapshot::getStates()->toArray());

it('will return the snapshotSource', function (): void {
    $organisation = Organisation::factory()
        ->create();
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
        ->create();
    $snapshot = Snapshot::factory()
        ->recycle($organisation)
        ->for($avgResponsibleProcessingRecord, 'snapshotSource')
        ->create();

    expect($snapshot->snapshotSource->id)
        ->toBe($avgResponsibleProcessingRecord->id);
});

it('will not return the snapshotSource if deleted', function (): void {
    $organisation = Organisation::factory()
        ->create();
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
        ->create([
            'deleted_at' => fake()->dateTime(),
        ]);
    $snapshot = Snapshot::factory()
        ->recycle($organisation)
        ->for($avgResponsibleProcessingRecord, 'snapshotSource')
        ->create();

    expect($snapshot->snapshotSource)
        ->toBeNull();
});
