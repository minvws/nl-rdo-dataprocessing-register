<?php

declare(strict_types=1);

namespace Tests\Feature\Listeners\Media;

use App\Models\SnapshotApproval;
use App\Models\User;
use App\Notifications\SnapshotApprovalSignRequest;
use Illuminate\Support\Facades\Notification;

use function it;

it('will trigger a notification', function (): void {
    Notification::fake();

    $user = User::factory()->create();

    SnapshotApproval::factory()
        ->create([
            'notified_at' => null,
            'assigned_to' => $user->id,
        ]);

    Notification::assertSentTo($user, SnapshotApprovalSignRequest::class);
});
