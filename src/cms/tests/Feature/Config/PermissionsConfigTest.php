<?php

declare(strict_types=1);

use App\Enums\Authorization\Permission;
use App\Enums\Authorization\Role;

it('has a permission mapping for every role', function (): void {
    $rolesAndPermissions = config('permissions.roles_and_permissions');

    foreach (Role::cases() as $role) {
        expect($rolesAndPermissions)->toHaveKey($role->value);
    }
});

it('grants the mandate-holder-manager role only the mandate-holder-manage permission', function (): void {
    $rolesAndPermissions = config('permissions.roles_and_permissions');

    expect($rolesAndPermissions[Role::MANDATE_HOLDER_MANAGER->value])
        ->toBe([Permission::USER_ROLE_ORGANISATION_MANDATE_HOLDER_MANAGE->value]);
});
