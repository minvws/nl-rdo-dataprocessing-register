<?php

declare(strict_types=1);

namespace App\Mail\SnapshotApproval;

use App\Filament\Resources\SnapshotResource\Pages\ViewSnapshot;
use App\Mail\Mailable;
use App\Models\SnapshotApproval;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailables\Content;

use function __;
use function sprintf;

class ApprovalNotification extends Mailable
{
    use Queueable;

    public string $link;

    public function __construct(
        public SnapshotApproval $snapshotApproval,
    ) {
        $this->link = ViewSnapshot::getUrl([
            'record' => $snapshotApproval->snapshot,
            'tenant' => $snapshotApproval->snapshot->organisation,
            'tab' => sprintf('-%s-tab', ViewSnapshot::TAB_ID_APPROVAL),
        ]);
    }

    public function getLogContext(): array
    {
        return [
            'snapshot_id' => $this->snapshotApproval->snapshot->id->toString(),
        ];
    }

    public function getSubject(): string
    {
        return __('snapshot_approval.mail_approval_notification_subject', [
            'status' => __(sprintf('snapshot_approval_status.%s', $this->snapshotApproval->status->value)),
        ]);
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.snapshot_approval.approval_notification');
    }
}
