<?php

declare(strict_types=1);

namespace App\Observers;

use App\Facades\AuditLog;
use App\Models\OrganisationUserRole;
use App\Services\AuditLog\User\UserPermissionUpdatedEvent;

class OrganisationUserRoleObserver
{
    public function saved(OrganisationUserRole $organisationUserRole): void
    {
        AuditLog::register(new UserPermissionUpdatedEvent($organisationUserRole->user));
    }
}
