<?php

declare(strict_types=1);

namespace App\Services\Snapshot;

use App\Enums\Authorization\Role;
use App\Enums\Snapshot\SnapshotApprovalLogMessageType;
use App\Enums\Snapshot\SnapshotApprovalStatus;
use App\Mail\SnapshotApprovalNotification;
use App\Mail\SnapshotApprovalRequest;
use App\Models\Snapshot;
use App\Models\SnapshotApproval;
use App\Models\SnapshotApprovalLog;
use App\Models\User;
use App\Services\User\UserByRoleService;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;

use function sprintf;

readonly class SnapshotApprovalService
{
    public function __construct(
        private UserByRoleService $userByRoleService,
    ) {
    }

    public function create(Snapshot $snapshot, User $requestedBy, User $assignedTo): void
    {
        $snapshotApproval = new SnapshotApproval();
        $snapshotApproval->snapshot_id = $snapshot->id;
        $snapshotApproval->requested_by = $requestedBy->id;
        $snapshotApproval->assigned_to = $assignedTo->id;
        $snapshotApproval->save();

        SnapshotApprovalLog::create([
            'snapshot_id' => $snapshot->id,
            'user_id' => $requestedBy->id,
            'message' => [
                'type' => SnapshotApprovalLogMessageType::APPROVAL_REQUEST,
                'assigned_to' => $this->getUserName($snapshotApproval->assignedTo),
            ],
        ]);
    }

    public function delete(SnapshotApproval $snapshotApproval, User $user): void
    {
        SnapshotApprovalLog::create([
            'snapshot_id' => $snapshotApproval->snapshot->id,
            'user_id' => $user->id,
            'message' => [
                'type' => SnapshotApprovalLogMessageType::APPROVAL_REQUEST_DELETE,
                'assigned_to' => $this->getUserName($snapshotApproval->assignedTo),
            ],
        ]);

        $snapshotApproval->delete();
    }

    public function notify(SnapshotApproval $snapshotApproval, User $user): void
    {
        Mail::to($snapshotApproval->assignedTo)
            ->queue(new SnapshotApprovalRequest($snapshotApproval));

        $snapshotApproval->notified_at = CarbonImmutable::now();
        $snapshotApproval->save();

        SnapshotApprovalLog::create([
            'snapshot_id' => $snapshotApproval->snapshot->id,
            'user_id' => $user->id,
            'message' => [
                'type' => SnapshotApprovalLogMessageType::APPROVAL_REQUEST_NOTIFY,
                'assigned_to' => $this->getUserName($snapshotApproval->assignedTo),
            ],
        ]);
    }

    public function setStatus(User $user, SnapshotApproval $snapshotApproval, SnapshotApprovalStatus $status, ?string $notes = null): void
    {
        $snapshotApproval->status = $status;
        $snapshotApproval->save();

        SnapshotApprovalLog::create([
            'snapshot_id' => $snapshotApproval->snapshot->id,
            'user_id' => $user->id,
            'message' => [
                'type' => SnapshotApprovalLogMessageType::APPROVAL_UPDATE,
                'assigned_to' => $this->getUserName($snapshotApproval->assignedTo),
                'status' => $status->value,
                'notes' => $notes,
            ],
        ]);

        /** @var Collection<int, User> $privacyOfficers */
        $privacyOfficers = $this->userByRoleService->getUsersByOrganisationRole(
            $snapshotApproval->snapshot->organisation,
            Role::PRIVACY_OFFICER,
        );

        foreach ($privacyOfficers as $privacyOfficer) {
            Mail::to($privacyOfficer)
                ->queue(new SnapshotApprovalNotification($snapshotApproval));

            SnapshotApprovalLog::create([
                'snapshot_id' => $snapshotApproval->snapshot->id,
                'user_id' => $user->id,
                'message' => [
                    'type' => SnapshotApprovalLogMessageType::APPROVAL_NOTIFY,
                    'assigned_to' => $this->getUserName($privacyOfficer),
                ],
            ]);
        }
    }

    private function getUserName(User $user): string
    {
        return sprintf('%s (%s)', $user->name, $user->email);
    }
}
