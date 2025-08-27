<?php

declare(strict_types=1);

use App\Enums\Authorization\Permission;
use App\Enums\Authorization\Role;
use App\Models\Organisation;
use App\Models\User;
use App\Services\AuthorizationService;
use Filament\Facades\Filament;
use Tests\Helpers\Model\UserTestHelper;

it('can check for a permission if none configured', function (): void {
    $organisation = Organisation::factory()->create();
    $user = User::factory()
        ->recycle($organisation)
        ->create();

    $this->be($user);
    Filament::setTenant($organisation);

    $authorizationService = $this->app->make(AuthorizationService::class, [
        'rolesAndPermissions' => [],
    ]);
    $permission = fake()->randomElement(Permission::cases());

    expect($authorizationService->hasPermission($permission))
        ->toBeFalse();
});

it('can check if user has role but role is not configured', function (): void {
    $organisation = Organisation::factory()->create();
    $user = UserTestHelper::createForOrganisation($organisation);
    $user->assignOrganisationRole(Role::FUNCTIONAL_MANAGER, $organisation);

    $this->be($user);
    Filament::setTenant($organisation);

    $authorizationService = $this->app->make(AuthorizationService::class, [
        'rolesAndPermissions' => [],
    ]);
    $permission = fake()->randomElement(Permission::cases());

    expect($authorizationService->hasPermission($permission))
        ->toBeFalse();
});

it('can check for a permission for user with global role', function (): void {
    $role = fake()->randomElement(Role::cases());
    $permission = fake()->randomElement(Permission::cases());

    $organisation = Organisation::factory()->create();
    $user = User::factory()
        ->recycle($organisation)
        ->hasGlobalRole($role)
        ->create();
    $this->be($user);
    Filament::setTenant($organisation);

    $authorizationService = $this->app->make(AuthorizationService::class, [
        'rolesAndPermissions' => [
            $role->value => [$permission->value],
        ],
    ]);

    expect($authorizationService->hasPermission($permission))
        ->toBeTrue();
});

it('can check for a permission for user with organisation role', function (): void {
    $role = fake()->randomElement(Role::cases());
    $permission = fake()->randomElement(Permission::cases());

    $organisation = Organisation::factory()->create();
    $user = User::factory()
        ->recycle($organisation)
        ->hasOrganisationRole($role, $organisation)
        ->create();
    $this->be($user);
    Filament::setTenant($organisation);

    $authorizationService = $this->app->make(AuthorizationService::class, [
        'rolesAndPermissions' => [
            $role->value => [$permission->value],
        ],
    ]);

    expect($authorizationService->hasPermission($permission))
        ->toBeTrue();
});

it('can check for a role if none configured', function (): void {
    $role = fake()->randomElement(Role::cases());

    $organisation = Organisation::factory()->create();
    $user = User::factory()
        ->recycle($organisation)
        ->create();

    $this->be($user);
    Filament::setTenant($organisation);

    $authorizationService = $this->app->get(AuthorizationService::class);

    expect($authorizationService->hasRole($role))
        ->toBeFalse();
});

it('can check for a role for user with global role', function (): void {
    $role = fake()->randomElement(Role::cases());

    $organisation = Organisation::factory()->create();
    $user = User::factory()
        ->recycle($organisation)
        ->hasGlobalRole($role)
        ->create();

    $this->be($user);
    Filament::setTenant($organisation);

    $authorizationService = $this->app->get(AuthorizationService::class);

    expect($authorizationService->hasRole($role))
        ->toBeTrue();
});

it('can check for a role for user with organisation role', function (): void {
    $role = fake()->randomElement(Role::cases());

    $organisation = Organisation::factory()->create();
    $user = User::factory()
        ->hasOrganisationRole($role, $organisation)
        ->create();

    $this->be($user);
    Filament::setTenant($organisation);

    $authorizationService = $this->app->get(AuthorizationService::class);

    expect($authorizationService->hasRole($role))
        ->toBeTrue();
});
