<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Authorization\Permission;
use App\Facades\Authorization;

class DataBreachRecordPolicy extends BasePolicy
{
    public function view(): bool
    {
        return Authorization::hasPermission(Permission::DATA_BREACH_RECORD_VIEW);
    }

    public function create(): bool
    {
        return Authorization::hasPermission(Permission::DATA_BREACH_RECORD_CREATE);
    }

    public function update(): bool
    {
        return Authorization::hasPermission(Permission::DATA_BREACH_RECORD_UPDATE);
    }

    public function delete(): bool
    {
        return Authorization::hasPermission(Permission::DATA_BREACH_RECORD_DELETE);
    }
}
