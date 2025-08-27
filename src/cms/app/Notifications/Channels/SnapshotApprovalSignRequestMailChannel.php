<?php

declare(strict_types=1);

namespace App\Notifications\Channels;

use App\Models\User;
use App\Notifications\SnapshotApprovalSignRequest;
use App\Services\Notification\SnapshotApprovalSignRequestMailer;

readonly class SnapshotApprovalSignRequestMailChannel
{
    public function __construct(
        private SnapshotApprovalSignRequestMailer $snapshotApprovalSignRequestMailer,
    ) {
    }

    public function send(User $user, SnapshotApprovalSignRequest $notification): void
    {
        $this->snapshotApprovalSignRequestMailer->handleNotification($user, $notification->getSnapshotApproval());
    }
}
