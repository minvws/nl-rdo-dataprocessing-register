<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Enums\Authorization\Role;
use App\Models\Organisation;
use App\Models\OrganisationUserRole;
use App\Models\UserGlobalRole;

trait HasRoles
{
    final public function assignGlobalRole(Role $role): static
    {
        UserGlobalRole::updateOrCreate([
            'user_id' => $this->id,
            'role' => $role->value,
        ]);

        return $this;
    }

    final public function assignOrganisationRole(Role $role, Organisation $organisation): static
    {
        OrganisationUserRole::updateOrCreate([
            'organisation_id' => $organisation->id,
            'user_id' => $this->id,
            'role' => $role->value,
        ]);

        return $this;
    }
}
