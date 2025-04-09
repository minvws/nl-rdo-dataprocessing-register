<?php

declare(strict_types=1);

use App\Models\Snapshot;
use App\Models\SnapshotTransition;
use App\Models\User;

it('belongs to a ProcessingRecord', function (): void {
    $snapshotTransition = SnapshotTransition::factory()->create();

    expect($snapshotTransition->snapshot)
        ->toBeInstanceOf(Snapshot::class)
        ->and($snapshotTransition->creator)
        ->toBeInstanceOf(User::class);
});
