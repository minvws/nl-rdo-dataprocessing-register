<?php

declare(strict_types=1);

use App\Enums\Authorization\Permission;
use App\Enums\Authorization\Role;
use App\Facades\Authorization;
use App\Models\Organisation;
use App\Models\User;
use Filament\Facades\Filament;

it('has permission if user with permission given', function (): void {
    $organisation = Organisation::factory()->create();
    $user = User::factory()
        ->hasAttached($organisation)
        ->hasOrganisationRole(Role::INPUT_PROCESSOR, $organisation)
        ->create();
    $this->be($user);
    Filament::setTenant($user->organisations->firstOrFail());

    expect(Authorization::hasPermission(Permission::SNAPSHOT_APPROVAL_CREATE))
        ->toBeTrue();
});

it('has no permission if user has no permission', function (): void {
    $user = User::factory()->withOrganisation()->create();
    $this->be($user);
    Filament::setTenant($user->organisations->firstOrFail());

    expect(Authorization::hasPermission(Permission::SNAPSHOT_APPROVAL_CREATE))
        ->toBeFalse();
});

it('has no permission if no user given', function (): void {
    expect(Authorization::hasPermission(Permission::SNAPSHOT_APPROVAL_CREATE))
        ->toBeFalse();
});

it('has global role', function (): void {
    $role = fake()->randomElement(Role::class);

    $user = User::factory()
        ->hasGlobalRole($role)
        ->create();
    $this->be($user);

    expect(Authorization::hasRole($role))
        ->toBeTrue();
});

it('has no global role', function (): void {
    $user = User::factory()->create();
    $this->be($user);

    expect(Authorization::hasRole(fake()->randomElement(Role::class)))
        ->toBeFalse();
});

it('has organisation role', function (): void {
    $role = fake()->randomElement(Role::class);

    $organisation = Organisation::factory()->create();
    $user = User::factory()
        ->hasAttached($organisation)
        ->hasOrganisationRole($role, $organisation)
        ->create();
    $this->be($user);
    Filament::setTenant($user->organisations->firstOrFail());

    expect(Authorization::hasRole($role))
        ->toBeTrue();
});

it('has no organisation role', function (): void {
    $organisation = Organisation::factory()->create();
    $user = User::factory()
        ->hasAttached($organisation)
        ->create();
    $this->be($user);
    Filament::setTenant($user->organisations->firstOrFail());

    expect(Authorization::hasRole(fake()->randomElement(Role::class)))
        ->toBeFalse();
});
