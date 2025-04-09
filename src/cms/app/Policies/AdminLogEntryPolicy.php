<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Authorization\Permission;
use App\Facades\Authorization;

class AdminLogEntryPolicy extends BasePolicy
{
    public function view(): bool
    {
        return Authorization::hasPermission(Permission::ADMIN_LOG_ENTRY_VIEW);
    }
}
