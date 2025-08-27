<?php

declare(strict_types=1);

namespace App\Mail\SnapshotApproval;

use App\Enums\RouteName;
use App\Enums\Snapshot\SnapshotApprovalLogMessageType;
use App\Mail\Mailable;
use App\Models\SnapshotApproval;
use App\Models\SnapshotApprovalLog;
use Carbon\CarbonImmutable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Mail\Factory;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\SentMessage;
use Illuminate\Support\Facades\URL;

use function __;

class SingleSignRequest extends Mailable
{
    use Queueable;

    public string $link;

    public function __construct(
        public SnapshotApproval $snapshotApproval,
    ) {
        $this->link = URL::signedRoute(RouteName::SNAPSHOT_SIGN_LOGIN_SINGLE_OPEN, [
            'snapshot_id' => $this->snapshotApproval->snapshot->id->toString(),
            'user_id' => $this->snapshotApproval->assignedTo->id->toString(),
        ]);
    }

    public function getLogContext(): array
    {
        return [
            'snapshot_id' => $this->snapshotApproval->snapshot->id->toString(),
            'user_id' => $this->snapshotApproval->assignedTo->id->toString(),
        ];
    }

    public function getSubject(): string
    {
        return __('snapshot_approval.mail_single_sign_request_subject');
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.snapshot_approval.single_sign_request');
    }

    /**
     * @param Factory|Mailer $mailer
     */
    public function send($mailer): ?SentMessage // phpcs:ignore SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
    {
        $sentMessage = parent::send($mailer);

        $this->snapshotApproval->update(['notified_at' => CarbonImmutable::now()]);
        $userId = $this->snapshotApproval->requestedBy !== null
            ? $this->snapshotApproval->requestedBy->id
            : $this->snapshotApproval->assignedTo->id;

        SnapshotApprovalLog::create([
            'snapshot_id' => $this->snapshotApproval->snapshot->id,
            'user_id' => $userId,
            'message' => [
                'type' => SnapshotApprovalLogMessageType::APPROVAL_REQUEST_NOTIFY,
                'assigned_to' => $this->snapshotApproval->assignedTo->logName,
            ],
        ]);

        return $sentMessage;
    }
}
