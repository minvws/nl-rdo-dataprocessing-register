<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\SnapshotApproval;
use App\Notifications\SnapshotApprovalSignRequest;

readonly class SnapshotApprovalObserver
{
    public function created(SnapshotApproval $snapshotApproval): void
    {
        $snapshotApproval->assignedTo->notify(new SnapshotApprovalSignRequest($snapshotApproval));
    }
}
