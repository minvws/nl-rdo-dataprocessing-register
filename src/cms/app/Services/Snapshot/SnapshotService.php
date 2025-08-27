<?php

declare(strict_types=1);

namespace App\Services\Snapshot;

use App\Models\Snapshot;
use App\Models\SnapshotApproval;

class SnapshotService
{
    public function countApproved(Snapshot $snapshot): int
    {
        return SnapshotApproval::where('snapshot_id', $snapshot->id)
            ->approved()
            ->count();
    }

    public function countTotal(Snapshot $snapshot): int
    {
        return SnapshotApproval::where('snapshot_id', $snapshot->id)
            ->count();
    }

    public function isApproved(Snapshot $snapshot): bool
    {
        $total = $this->countTotal($snapshot);
        if ($total === 0) {
            return false;
        }

        return $total === $this->countApproved($snapshot);
    }
}
