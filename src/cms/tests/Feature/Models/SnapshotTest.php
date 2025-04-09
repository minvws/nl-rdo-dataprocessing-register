<?php

declare(strict_types=1);

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
