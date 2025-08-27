<?php

declare(strict_types=1);

namespace App\Services\Snapshot;

use App\Collections\SnapshotCollection;
use App\Models\Contracts\SnapshotSource;
use App\Models\Snapshot;
use App\Models\States\SnapshotState;
use Illuminate\Support\Facades\DB;
use Webmozart\Assert\Assert;

class SnapshotStateTransitionService
{
    public function transitionToSnapshotState(Snapshot $snapshot, SnapshotState $toState): void
    {
        DB::transaction(function () use ($snapshot, $toState): void {
            $snapshotSource = $snapshot->snapshotSource()->withTrashed()->first();
            Assert::nullOrIsInstanceOf($snapshotSource, SnapshotSource::class);

            if ($snapshotSource !== null) {
                $this->transitionSnapshotsToObsolete($snapshotSource->getSnapshotsWithState($toState::class));
            }

            $snapshot->state->transitionTo($toState);
        });
    }

    public function transitionSnapshotsToObsolete(SnapshotCollection $snapshots): void
    {
        $snapshots->each(static function (Snapshot $snapshot): void {
            $snapshot->state->transitionTo(SnapshotState::OBSOLETE_STATE);
        });
    }
}
