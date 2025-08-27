<?php

declare(strict_types=1);

use App\Enums\Authorization\Role;
use App\Models\Organisation;
use App\Models\User;
use App\Services\AuthenticationService;
use Filament\Facades\Filament;

it('will return the correct organisation', function (): void {
    $organisation = Organisation::factory()->create();
    $user = User::factory()
        ->recycle($organisation)
        ->create();

    $this->be($user);
    Filament::setTenant($organisation);

    $authenticationService = $this->app->get(AuthenticationService::class);

    expect($authenticationService->organisation())
        ->toBe($organisation);
});

it('will fail if no organisation', function (): void {
    $authenticationService = $this->app->get(AuthenticationService::class);

    $authenticationService->organisation();
})->throws(InvalidArgumentException::class);

it('will return the correct user', function (): void {
    $user = User::factory()->create();
    $this->be($user);

    $authenticationService = $this->app->get(AuthenticationService::class);

    expect($authenticationService->user())
        ->toBe($user);
});

it('will fail if no user', function (): void {
    $authenticationService = $this->app->get(AuthenticationService::class);

    $authenticationService->user();
})->throws(InvalidArgumentException::class);

it('will return the correct principal roles when no roles', function (): void {
    $authenticationService = $this->app->get(AuthenticationService::class);

    $principal = $authenticationService->principal();
    expect($principal->roles)
        ->toBe([]);
});

it('will return the correct principal roles when only one global role', function (): void {
    $role = fake()->randomElement(Role::cases());

    $user = User::factory()
        ->hasGlobalRole($role)
        ->create();
    $this->be($user);

    $authenticationService = $this->app->get(AuthenticationService::class);

    $principal = $authenticationService->principal();
    expect($principal->roles)
        ->toBe([$role]);
});

it('will return the correct principal roles when only one organisation role', function (): void {
    $role = fake()->randomElement(Role::cases());

    $organisation = Organisation::factory()->create();
    $user = User::factory()
        ->hasOrganisationRole($role, $organisation)
        ->create();
    $this->be($user);
    Filament::setTenant($organisation);

    $authenticationService = $this->app->get(AuthenticationService::class);

    $principal = $authenticationService->principal();
    expect($principal->roles)
        ->toBe([$role]);
});

it('will return the correct principal roles with global and organisation roles', function (): void {
    $globalRoles = fake()->randomElements(Role::cases(), fake()->numberBetween(1, 3));
    $organisationRoles = fake()->randomElements(Role::cases(), fake()->numberBetween(1, 3));

    $organisation = Organisation::factory()->create();
    $userFactory = User::factory();
    foreach ($globalRoles as $globalRole) {
        $userFactory = $userFactory->hasGlobalRole($globalRole);
    }
    foreach ($organisationRoles as $organisationRole) {
        $userFactory = $userFactory->hasOrganisationRole($organisationRole, $organisation);
    }
    $user = $userFactory->create();

    $this->be($user);
    Filament::setTenant($organisation);

    $authenticationService = $this->app->get(AuthenticationService::class);

    $principal = $authenticationService->principal();
    expect($principal->roles)
        ->toBe(array_merge($globalRoles, $organisationRoles));
});
