<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Authorization\Permission;
use App\Facades\Authorization;

class UserPolicy extends BasePolicy
{
    public function view(): bool
    {
        return Authorization::hasPermission(Permission::USER_VIEW);
    }

    public function create(): bool
    {
        return Authorization::hasPermission(Permission::USER_CREATE);
    }

    public function update(): bool
    {
        return Authorization::hasPermission(Permission::USER_UPDATE);
    }

    public function delete(): bool
    {
        return Authorization::hasPermission(Permission::USER_DELETE);
    }
}
