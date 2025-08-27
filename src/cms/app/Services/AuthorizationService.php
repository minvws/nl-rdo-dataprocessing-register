<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\Authorization\Permission;
use App\Enums\Authorization\Role;

use function array_any;
use function array_key_exists;
use function in_array;

readonly class AuthorizationService
{
    /**
     * @param array<class-string<Role>, array<class-string<Permission>>> $rolesAndPermissions
     */
    public function __construct(
        private AuthenticationService $authenticationService,
        private array $rolesAndPermissions,
    ) {
    }

    public function hasPermission(Permission $permission): bool
    {
        $principal = $this->authenticationService->principal();

        return array_any($principal->roles, function ($role) use ($permission): bool {
            if (!array_key_exists($role->value, $this->rolesAndPermissions)) {
                return false;
            }

            return in_array($permission->value, $this->rolesAndPermissions[$role->value], true);
        });
    }

    public function hasRole(Role $role): bool
    {
        $principal = $this->authenticationService->principal();

        return in_array($role, $principal->roles, true);
    }
}
