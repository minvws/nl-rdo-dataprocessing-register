<?php

declare(strict_types=1);

use App\Models\PublicWebsiteCheck;
use App\Models\PublicWebsiteSnapshotEntry;
use App\Models\Snapshot;

it('has a snapshot', function (): void {
    $publicWebstiteSnapshotEntry = PublicWebsiteSnapshotEntry::factory()
        ->create();

    expect($publicWebstiteSnapshotEntry->snapshot)
        ->toBeInstanceOf(Snapshot::class);
});

it('has a last public website check', function (): void {
    $publicWebstiteSnapshotEntry = PublicWebsiteSnapshotEntry::factory()
        ->create();

    expect($publicWebstiteSnapshotEntry->lastPublicWebsiteCheck)
        ->toBeInstanceOf(PublicWebsiteCheck::class);
});
