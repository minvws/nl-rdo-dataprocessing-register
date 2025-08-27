<?php

declare(strict_types=1);

namespace App\Mail\SnapshotApproval;

use App\Collections\SnapshotApprovalCollection;
use App\Enums\RouteName;
use App\Mail\Mailable;
use App\Models\Organisation;
use App\Models\SnapshotApproval;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Support\Facades\URL;
use Webmozart\Assert\Assert;

use function __;

class BatchSignRequest extends Mailable
{
    use Queueable;

    public string $link;

    public function __construct(
        public User $user,
        public Organisation $organisation,
        public SnapshotApprovalCollection $snapshotApprovalsNew,
        public SnapshotApprovalCollection $snapshotApprovalsExisting,
    ) {
        $this->link = URL::signedRoute(RouteName::SNAPSHOT_SIGN_LOGIN_BATCH_OPEN, [
            'organisation_id' => $this->organisation->id->toString(),
            'user_id' => $user->id->toString(),
        ]);
    }

    public function getLogContext(): array
    {
        return [
            'user_id' => $this->user->id->toString(),
            'new_snapshot_ids' => $this->getSnapshotIds($this->snapshotApprovalsNew),
            'existing_snapshot_ids' => $this->getSnapshotIds($this->snapshotApprovalsExisting),
        ];
    }

    public function getSubject(): string
    {
        return __('snapshot_approval.mail_batch_sign_request_subject', ['organisationName' => $this->organisation->name]);
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.snapshot_approval.batch_sign_request');
    }

    /**
     * @return array<string>
     */
    private function getSnapshotIds(SnapshotApprovalCollection $snapshotApprovalsExisting): array
    {
        $snapshotIds = $snapshotApprovalsExisting->map(static function (SnapshotApproval $snapshotApproval): string {
                return $snapshotApproval->id->toString();
        })->toArray();
        Assert::allString($snapshotIds);

        return $snapshotIds;
    }
}
