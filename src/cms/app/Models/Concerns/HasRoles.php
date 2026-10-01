<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Enums\Authorization\Role;
use App\Models\Organisation;
use App\Models\OrganisationUserRole;
use App\Models\UserGlobalRole;
use Illuminate\Support\Facades\DB;

use function array_filter;
use function in_array;

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

    /**
     * Sets the user's roles in the organisation to $selectedRoles, limited to the roles in $manageableRoles.
     * The mandate holder manager role is only kept when the user holds the privacy officer role afterwards.
     *
     * @param array<Role> $manageableRoles
     * @param array<Role> $selectedRoles
     */
    final public function syncOrganisationRoles(
        Organisation $organisation,
        array $manageableRoles,
        array $selectedRoles,
    ): static {
        DB::transaction(function () use ($organisation, $manageableRoles, $selectedRoles): void {
            $unmanagedRoles = OrganisationUserRole::query()
                ->where('organisation_id', $organisation->id)
                ->where('user_id', $this->id)
                ->whereNotIn('role', $manageableRoles)
                ->pluck('role')
                ->all();

            if (!in_array(Role::PRIVACY_OFFICER, [...$unmanagedRoles, ...$selectedRoles], true)) {
                $manageableRoles[] = Role::MANDATE_HOLDER_MANAGER;
                $selectedRoles = array_filter($selectedRoles, static fn (Role $role): bool => $role !== Role::MANDATE_HOLDER_MANAGER);
            }

            OrganisationUserRole::query()
                ->where('organisation_id', $organisation->id)
                ->where('user_id', $this->id)
                ->whereIn('role', $manageableRoles)
                ->delete();

            foreach ($selectedRoles as $selectedRole) {
                $this->assignOrganisationRole($selectedRole, $organisation);
            }
        });

        return $this;
    }
}
