<?php

declare(strict_types=1);

use App\Filament\Actions\SnapshotTransition\ObsoleteAction;
use App\Models\Snapshot;
use App\Models\States\Snapshot\Obsolete;
use App\Models\States\SnapshotState;

it('can make the action', function (): void {
    $snapshot = Snapshot::factory()->create();
    $snapshotState = SnapshotState::make(Obsolete::$name, $snapshot);
    $inReviewAction = ObsoleteAction::makeForSnapshotState($snapshot, $snapshotState);

    expect($inReviewAction)
        ->toBeInstanceOf(ObsoleteAction::class);
});
