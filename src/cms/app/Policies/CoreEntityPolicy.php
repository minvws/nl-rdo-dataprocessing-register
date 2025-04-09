<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Authorization\Permission;
use App\Facades\Authorization;

class CoreEntityPolicy extends BasePolicy
{
    public function view(): bool
    {
        return Authorization::hasPermission(Permission::CORE_ENTITY_VIEW);
    }

    public function create(): bool
    {
        return Authorization::hasPermission(Permission::CORE_ENTITY_CREATE);
    }

    public function update(): bool
    {
        return Authorization::hasPermission(Permission::CORE_ENTITY_UPDATE);
    }

    public function delete(): bool
    {
        return Authorization::hasPermission(Permission::CORE_ENTITY_DELETE);
    }
}
