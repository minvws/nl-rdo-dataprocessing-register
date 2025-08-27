<?php

declare(strict_types=1);

namespace App\Models\States\Transitions;

use App\Facades\Authentication;
use App\Models\Snapshot;
use App\Models\SnapshotTransition;
use App\Models\States\SnapshotState;
use Spatie\ModelStates\Transition;

abstract class StateTransition extends Transition
{
    public function __construct(
        protected readonly Snapshot $snapshot,
    ) {
    }

    abstract public function handle(): Snapshot;

    /**
     * @param class-string<SnapshotState> $stateClass
     */
    protected function transitionToState(string $stateClass): void
    {
        $this->snapshot->state = new $stateClass($this->snapshot);
        $this->snapshot->save();

        SnapshotTransition::create([
            'snapshot_id' => $this->snapshot->id,
            'created_by' => Authentication::user()->id,
            'state' => $this->snapshot->state,
        ]);
    }
}
