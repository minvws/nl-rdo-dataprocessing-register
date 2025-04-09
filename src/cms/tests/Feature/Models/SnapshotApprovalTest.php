<?php

declare(strict_types=1);

use App\Enums\Snapshot\SnapshotApprovalStatus;
use App\Models\SnapshotApproval;

it('can hold all approval-states', function (SnapshotApprovalStatus $snapshotApprovalStatus): void {
    $snapshotApproval = SnapshotApproval::factory()->create([
        'status' => $snapshotApprovalStatus,
    ]);

    expect($snapshotApproval->status)
        ->toBe($snapshotApprovalStatus);
})->with(SnapshotApprovalStatus::cases());
