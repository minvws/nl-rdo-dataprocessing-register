<?php

declare(strict_types=1);

namespace App\Mail\SnapshotApproval;

use App\Filament\Resources\SnapshotResource\Pages\ViewSnapshot;
use App\Mail\Mailable;
use App\Models\Snapshot;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailables\Content;

use function __;
use function sprintf;

class ApprovalRequest extends Mailable
{
    use Queueable;

    public string $link;

    public function __construct(
        public Snapshot $snapshot,
    ) {
        $this->link = ViewSnapshot::getUrl([
            'record' => $snapshot,
            'tenant' => $snapshot->organisation,
            'tab' => sprintf('-%s-tab', ViewSnapshot::TAB_ID_INFO),
        ]);
    }

    public function getLogContext(): array
    {
        return [
            'snapshot_id' => $this->snapshot->id->toString(),
        ];
    }

    public function getSubject(): string
    {
        return __('snapshot_approval.mail_approval_request_subject');
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.snapshot_approval.approval_request');
    }
}
