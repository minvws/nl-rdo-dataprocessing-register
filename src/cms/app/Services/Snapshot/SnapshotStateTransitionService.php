<?php

declare(strict_types=1);

namespace App\Services\Snapshot;

use App\Facades\Authentication;
use App\Models\Contracts\SnapshotSource;
use App\Models\Snapshot;
use App\Models\SnapshotTransition;
use App\Models\States\Snapshot\Obsolete;
use App\Models\States\SnapshotState;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Webmozart\Assert\Assert;

use function now;

class SnapshotStateTransitionService
{
    public function transitionToSnapshotState(Snapshot $snapshot, SnapshotState $toState): void
    {
        DB::transaction(function () use ($snapshot, $toState): void {
            $snapshotSource = $snapshot->snapshotSource;
            Assert::isInstanceOf($snapshotSource, SnapshotSource::class);

            $this->transitionSnapshotsToObsolete($snapshotSource, $toState);
            $this->registerTransition($snapshot, $toState);

            $snapshot->state->transitionTo($toState);
            if ($toState instanceof Obsolete) {
                $snapshot->replaced_at = now();
            }
            $snapshot->save();
        });
    }

    public function transitionSnapshotsToObsolete(SnapshotSource $snapshotSource, SnapshotState $snapshotState): void
    {
        // if snapshot state is obsolete, we don't need to obsolete any other snapshots
        if ($snapshotState instanceof Obsolete) {
            return;
        }

        /** @var Collection<int, Snapshot> $snapshotsToObsolete */
        $snapshotsToObsolete = $snapshotSource
            ->snapshots()
            ->where(['state' => $snapshotState])
            ->get();

        $snapshotsToObsolete->each(static function (Snapshot $snapshot): void {
            $snapshot->state->transitionTo(SnapshotState::OBSOLETE_STATE);
            $snapshot->replaced_at = now();
            $snapshot->save();
        });
    }

    private function registerTransition(Snapshot $snapshot, SnapshotState $state): void
    {
        SnapshotTransition::create([
            'snapshot_id' => $snapshot->id,
            'created_by' => Authentication::user()->id,
            'state' => $state,
        ]);
    }
}
