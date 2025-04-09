<?php

declare(strict_types=1);

use App\Filament\Actions\SnapshotTransition\InReviewAction;
use App\Models\Snapshot;
use App\Models\States\Snapshot\InReview;
use App\Models\States\SnapshotState;

it('can make the action', function (): void {
    $snapshot = Snapshot::factory()->create();
    $snapshotState = SnapshotState::make(InReview::$name, $snapshot);
    $inReviewAction = InReviewAction::makeForSnapshotState($snapshot, $snapshotState);

    expect($inReviewAction)
        ->toBeInstanceOf(InReviewAction::class);
});
