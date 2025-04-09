<?php

declare(strict_types=1);

namespace App\Services\Snapshot;

use App\Models\Snapshot;

class SnapshotService
{
    public function countApproved(Snapshot $snapshot): int
    {
        return $snapshot->snapshotApprovals()
            ->approved()
            ->count();
    }

    public function countTotal(Snapshot $snapshot): int
    {
        return $snapshot->snapshotApprovals()
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
