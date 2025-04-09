<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Authorization\Permission;
use App\Facades\Authorization;

class ResponsibleLegalEntityPolicy extends BasePolicy
{
    public function view(): bool
    {
        return Authorization::hasPermission(Permission::RESPONSIBLE_LEGAL_ENTITY_VIEW);
    }

    public function create(): bool
    {
        return Authorization::hasPermission(Permission::RESPONSIBLE_LEGAL_ENTITY_CREATE);
    }

    public function update(): bool
    {
        return Authorization::hasPermission(Permission::RESPONSIBLE_LEGAL_ENTITY_UPDATE);
    }

    public function delete(): bool
    {
        return Authorization::hasPermission(Permission::RESPONSIBLE_LEGAL_ENTITY_DELETE);
    }
}
