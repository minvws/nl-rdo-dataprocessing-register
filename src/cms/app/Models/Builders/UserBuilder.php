<?php

declare(strict_types=1);

namespace App\Models\Builders;

use App\Models\Organisation;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

/**
 * @extends Builder<User>
 */
class UserBuilder extends Builder
{
    public function withOrganisation(Organisation $organisation): self
    {
        return $this->whereRelation('organisations', 'id', $organisation->id->toString());
    }

    public function withUnsignedSnapshotApprovals(): self
    {
        return $this->whereRelation('snapshotApprovals', static function (SnapshotApprovalBuilder $snapshotApprovalBuilder): void {
            $snapshotApprovalBuilder->unsigned();
        });
    }
}
