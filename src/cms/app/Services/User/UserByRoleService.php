<?php

declare(strict_types=1);

namespace App\Services\User;

use App\Enums\Authorization\Role;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class UserByRoleService
{
    /**
     * @return Collection<int, User>
     */
    public function getUsersByGlobalRole(Role $role): Collection
    {
        return User::whereHas('globalRoles', static function (Builder $query) use ($role): void {
                $query->where(['role' => $role]);
        })
            ->get();
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsersByOrganisationRole(Organisation $organisation, Role $role): Collection
    {
        /** @var Collection<int, User> $users */
        $users = $organisation
            ->users()
            ->whereHas('organisationRoles', static function (Builder $query) use ($role): void {
                $query->where(['role' => $role]);
            })
            ->get();

        return $users;
    }
}
