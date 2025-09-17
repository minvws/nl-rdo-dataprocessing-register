<?php

declare(strict_types=1);

use App\Models\Snapshot;
use App\Models\StaticWebsiteCheck;
use App\Models\StaticWebsiteSnapshotEntry;

it('has a snapshot', function (): void {
    $staticWebstiteSnapshotEntry = StaticWebsiteSnapshotEntry::factory()
        ->create();

    expect($staticWebstiteSnapshotEntry->snapshot)
        ->toBeInstanceOf(Snapshot::class);
});

it('has a last static website check', function (): void {
    $staticWebstiteSnapshotEntry = StaticWebsiteSnapshotEntry::factory()
        ->create();

    expect($staticWebstiteSnapshotEntry->lastStaticWebsiteCheck)
        ->toBeInstanceOf(StaticWebsiteCheck::class);
});
