<?php

declare(strict_types=1);

use App\Filament\Actions\SnapshotTransition\ApproveAction;
use App\Models\Snapshot;
use App\Models\States\Snapshot\Approved;
use App\Models\States\SnapshotState;

it('can make the action', function (): void {
    $snapshot = Snapshot::factory()->create();
    $snapshotState = SnapshotState::make(Approved::$name, $snapshot);
    $inReviewAction = ApproveAction::makeForSnapshotState($snapshot, $snapshotState);

    expect($inReviewAction)
        ->toBeInstanceOf(ApproveAction::class);
});
