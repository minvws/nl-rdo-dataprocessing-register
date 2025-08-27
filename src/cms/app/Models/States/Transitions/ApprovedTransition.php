<?php

declare(strict_types=1);

namespace App\Models\States\Transitions;

use App\Models\Snapshot;
use App\Models\States\Snapshot\Approved;
use App\Notifications\SnapshotApprovalSignRequest;

class ApprovedTransition extends StateTransition
{
    public function handle(): Snapshot
    {
        $this->transitionToState(Approved::class);

        foreach ($this->snapshot->snapshotApprovals as $snapshotApproval) {
            $snapshotApproval->assignedTo->notify(new SnapshotApprovalSignRequest($snapshotApproval));
        }

        return $this->snapshot;
    }
}
