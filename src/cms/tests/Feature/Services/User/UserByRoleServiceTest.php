<?php

declare(strict_types=1);

use App\Enums\Authorization\Role;
use App\Models\Organisation;
use App\Models\User;
use App\Services\User\UserByRoleService;

it('returns no users with given organisation role when none available', function (): void {
    $role = fake()->randomElement(Role::cases());

    $organisation = Organisation::factory()->create();

    $userByRoleService = $this->app->get(UserByRoleService::class);
    $result = $userByRoleService->getUsersByOrganisationRole($organisation, [$role]);

    $this->assertCount(0, $result);
});

it('returns users with given organisation role when available', function (): void {
    $role = fake()->randomElement(Role::cases());
    $count = fake()->numberBetween(1, 3);

    $organisation = Organisation::factory()->create();
    User::factory()
        ->hasAttached($organisation)
        ->hasOrganisationRole($role, $organisation)
        ->count($count)
        ->create();

    // extra users, but NOT attached to the organisation
    User::factory()
        ->hasOrganisationRole(fake()->unique()->randomElement(Role::cases()), $organisation)
        ->count(fake()->numberBetween(1, 3))
        ->create();

    $userByRoleService = $this->app->get(UserByRoleService::class);
    $result = $userByRoleService->getUsersByOrganisationRole($organisation, [$role]);

    $this->assertCount($count, $result);
});

it('returns users with mutliple given organisation role when available', function (): void {
    $organisation = Organisation::factory()->create();

    $role1 = fake()->unique()->randomElement(Role::cases());
    User::factory()
        ->hasAttached($organisation)
        ->hasOrganisationRole($role1, $organisation)
        ->create();

    $role2 = fake()->unique()->randomElement(Role::cases());
    User::factory()
        ->hasAttached($organisation)
        ->hasOrganisationRole($role2, $organisation)
        ->create();

    $userByRoleService = $this->app->get(UserByRoleService::class);
    $result = $userByRoleService->getUsersByOrganisationRole($organisation, [$role1, $role2]);

    $this->assertCount(2, $result);
});
