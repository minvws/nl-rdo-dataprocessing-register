<?php

declare(strict_types=1);

namespace App\Observers;

use App\Facades\AuditLog;
use App\Models\UserOrganisationRole;
use App\Services\AuditLog\User\UserPermissionUpdatedEvent;

class UserOrganisationRoleObserver
{
    public function saved(UserOrganisationRole $organisationRole): void
    {
        AuditLog::register(new UserPermissionUpdatedEvent($organisationRole->user));
    }
}
