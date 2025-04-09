<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Authorization\Permission;
use App\Facades\Authorization;

class OrganisationPolicy extends BasePolicy
{
    public function view(): bool
    {
        return Authorization::hasPermission(Permission::ORGANISATION_VIEW);
    }

    public function create(): bool
    {
        return Authorization::hasPermission(Permission::ORGANISATION_CREATE);
    }

    public function update(): bool
    {
        return Authorization::hasPermission(Permission::ORGANISATION_UPDATE);
    }

    public function delete(): bool
    {
        return Authorization::hasPermission(Permission::ORGANISATION_DELETE);
    }
}
