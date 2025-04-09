<?php

declare(strict_types=1);

namespace App\Mail;

use App\Filament\Resources\SnapshotResource\Pages\ViewSnapshot;
use App\Models\SnapshotApproval;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;

use function __;
use function sprintf;

class SnapshotApprovalRequest extends Mailable
{
    use Queueable;
    use SerializesModels;

    public string $link;
    public SnapshotApproval $snapshotApproval;

    public function __construct(SnapshotApproval $snapshotApproval)
    {
        $this->snapshotApproval = $snapshotApproval;
        $this->link = ViewSnapshot::getUrl([
            'record' => $snapshotApproval->snapshot,
            'tenant' => $snapshotApproval->snapshot->organisation,
            'tab' => sprintf('-%s-tab', ViewSnapshot::TAB_ID_INFO),
        ]);
    }

    public function getSubject(): string
    {
        return __('snapshot_approval.mail_request_subject');
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.snapshot_approval.request');
    }
}
