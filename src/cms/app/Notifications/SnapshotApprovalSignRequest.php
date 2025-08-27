<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\SnapshotApproval;
use App\Notifications\Channels\SnapshotApprovalSignRequestMailChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class SnapshotApprovalSignRequest extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly SnapshotApproval $snapshotApproval,
    ) {
    }

    /**
     * @return array<string>
     */
    public function via(): array
    {
        return [
            SnapshotApprovalSignRequestMailChannel::class,
        ];
    }

    public function getSnapshotApproval(): SnapshotApproval
    {
        return $this->snapshotApproval;
    }
}
