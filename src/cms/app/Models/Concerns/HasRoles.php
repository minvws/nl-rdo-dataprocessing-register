<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Components\Uuid\Uuid;
use App\Enums\Authorization\Role;
use App\Models\Organisation;
use Illuminate\Support\Facades\DB;

trait HasRoles
{
    public function assignGlobalRole(Role $role): static
    {
        DB::table('user_global_roles')
            ->insertOrIgnore([
                'id' => Uuid::generate()->toString(),
                'user_id' => $this->id,
                'role' => $role->value,
            ]);

        return $this;
    }

    public function assignOrganisationRole(Role $role, Organisation $organisation): static
    {
        DB::table('user_organisation_roles')
            ->insertOrIgnore([
                'id' => Uuid::generate()->toString(),
                'organisation_id' => $organisation->id,
                'user_id' => $this->id,
                'role' => $role->value,
            ]);

        return $this;
    }
}
