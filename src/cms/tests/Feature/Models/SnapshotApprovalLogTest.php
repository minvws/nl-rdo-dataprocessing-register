<?php

declare(strict_types=1);

use App\Models\SnapshotApprovalLog;
use App\Models\User;

it('can retrieve the user from the snapshot approval log', function (): void {
    $user = User::factory()->create();
    $snapshotApprovalLog = SnapshotApprovalLog::factory([
        'user_id' => $user->id,
    ])->create();

    expect($snapshotApprovalLog->user->id)
        ->toBe($user->id);
});
