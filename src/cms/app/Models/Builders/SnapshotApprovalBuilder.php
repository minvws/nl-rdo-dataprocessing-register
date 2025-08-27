<?php

declare(strict_types=1);

namespace App\Models\Builders;

use App\Enums\Snapshot\SnapshotApprovalStatus;
use App\Models\Organisation;
use App\Models\SnapshotApproval;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

/**
 * @extends Builder<SnapshotApproval>
 */
class SnapshotApprovalBuilder extends Builder
{
    public function approved(): self
    {
        return $this->where('status', SnapshotApprovalStatus::APPROVED);
    }

    public function assignedTo(User $user): self
    {
        return $this->where('assigned_to', $user->id);
    }

    public function notified(): self
    {
        return $this->whereNotNull('notified_at');
    }

    public function notNotified(): self
    {
        return $this->whereNull('notified_at');
    }

    public function signed(): self
    {
        return $this->whereIn('status', SnapshotApprovalStatus::signed());
    }

    public function unsigned(): self
    {
        return $this->whereIn('status', SnapshotApprovalStatus::unsigned());
    }

    public function whereSnapshotOrganisation(Organisation $organisation): self
    {
        return $this->whereRelation('snapshot', static function (SnapshotBuilder $snapshotBuilder) use ($organisation): void {
            $snapshotBuilder->whereOrganisation($organisation);
        });
    }
}
