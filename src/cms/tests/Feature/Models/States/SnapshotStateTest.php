<?php

declare(strict_types=1);

namespace Tests\Unit\Models\Concerns;

use App\Filament\Actions\SnapshotTransition\ApproveAction;
use App\Filament\Actions\SnapshotTransition\EstablishAction;
use App\Filament\Actions\SnapshotTransition\InReviewAction;
use App\Filament\Actions\SnapshotTransition\ObsoleteAction;
use App\Models\Snapshot;
use App\Models\States\Snapshot\Approved;
use App\Models\States\Snapshot\Established;
use App\Models\States\Snapshot\InReview;
use App\Models\States\Snapshot\Obsolete;

use function expect;
use function it;

it('returns the correct action', function (string $state, string $expectedAction): void {
    $snapshot = Snapshot::factory()->create(['state' => $state]);

    expect($snapshot->state->getAction())
        ->toBe($expectedAction);
})->with([
    [Approved::class, ApproveAction::class],
    [Established::class, EstablishAction::class],
    [InReview::class, InReviewAction::class],
    [Obsolete::class, ObsoleteAction::class],
]);
