<?php

declare(strict_types=1);

namespace App\Services\User;

use App\Collections\UserCollection;
use App\Enums\Authorization\Role;
use App\Models\Organisation;
use Illuminate\Database\Eloquent\Builder;
use Webmozart\Assert\Assert;

class UserByRoleService
{
    /**
     * @param array<Role> $roles
     */
    public function getUsersByOrganisationRole(Organisation $organisation, array $roles): UserCollection
    {
        $users = $organisation
            ->users()
            ->whereHas('organisationRoles', static function (Builder $query) use ($roles): void {
                $query->whereIn('role', $roles);
            })
            ->get();

        Assert::isInstanceOf($users, UserCollection::class);

        return $users;
    }
}
