<?php

declare(strict_types=1);

namespace App\Services\Notification;

use App\Collections\SnapshotApprovalCollection;
use App\Enums\Snapshot\MandateholderNotifyDirectly;
use App\Mail\SnapshotApproval\BatchSignRequest;
use App\Mail\SnapshotApproval\SingleSignRequest;
use App\Models\Organisation;
use App\Models\SnapshotApproval;
use App\Models\States\Snapshot\Approved;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Mail;
use Psr\Log\LoggerInterface;
use Webmozart\Assert\Assert;

readonly class SnapshotApprovalSignRequestMailer
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    public function handleNotification(User $user, SnapshotApproval $snapshotApproval): void
    {
        if (!$snapshotApproval->snapshot->state instanceof Approved) {
            return;
        }

        match ($user->mandateholder_notify_directly) {
            MandateholderNotifyDirectly::SINGLE => $this->handleSingle($snapshotApproval),
            MandateholderNotifyDirectly::BATCH => $this->handleBatch($user, $snapshotApproval->snapshot->organisation),
            default => $this->handleNone($user),
        };
    }

    public function handleWeekly(): void
    {
        $users = User::withUnsignedSnapshotApprovals()->get();

        foreach ($users as $user) {
            foreach ($user->organisations as $organisation) {
                $this->handleBatch($user, $organisation);
            }
        }
    }

    private function handleNone(User $user): void
    {
        $this->logger->debug('handled notification directly', [
            'user_id' => $user->id->toString(),
        ]);
    }

    private function handleBatch(User $user, Organisation $organisation): void
    {
        $snapshotApprovalsNew = SnapshotApproval::unsigned()
            ->assignedTo($user)
            ->whereSnapshotOrganisation($organisation)
            ->notNotified()
            ->get();
        Assert::isInstanceOf($snapshotApprovalsNew, SnapshotApprovalCollection::class);

        $snapshotApprovalsExisting = SnapshotApproval::unsigned()
            ->assignedTo($user)
            ->whereSnapshotOrganisation($organisation)
            ->notified()
            ->get();
        Assert::isInstanceOf($snapshotApprovalsExisting, SnapshotApprovalCollection::class);

        if ($snapshotApprovalsNew->isEmpty() && $snapshotApprovalsExisting->isEmpty()) {
            return;
        }

        Mail::to($user)
            ->queue(new BatchSignRequest($user, $organisation, $snapshotApprovalsNew, $snapshotApprovalsExisting));

        SnapshotApproval::whereIn('id', $snapshotApprovalsNew->pluck('id'))
            ->update(['notified_at' => CarbonImmutable::now()]);
    }

    private function handleSingle(SnapshotApproval $snapshotApproval): void
    {
        Mail::to($snapshotApproval->assignedTo)
            ->queue(new SingleSignRequest($snapshotApproval));
    }
}
