<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\Authorization\Permission;
use App\Enums\Authorization\Role;

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
        $principal = $this->authenticationService->getPrincipal();

        foreach ($principal->roles as $role) {
            if (in_array($permission->value, $this->rolesAndPermissions[$role->value], true)) {
                return true;
            }
        }

        return false;
    }

    public function hasRole(Role $role): bool
    {
        $principal = $this->authenticationService->getPrincipal();

        return in_array($role, $principal->roles, true);
    }
}
