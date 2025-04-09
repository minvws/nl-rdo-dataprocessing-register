<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Authorization\Permission;
use App\Facades\Authorization;

class SnapshotPolicy extends BasePolicy
{
    public function view(): bool
    {
        return Authorization::hasPermission(Permission::SNAPSHOT_VIEW);
    }
}
