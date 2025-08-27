<?php

declare(strict_types=1);

use App\Enums\Authorization\Role;
use App\Filament\Resources\OrganisationUserResource;
use App\Filament\Resources\OrganisationUserResource\Pages\ListOrganisationUsers;
use App\Models\User;
use Tests\Helpers\Model\OrganisationTestHelper;

it('loads the list page', function (): void {
    $this->asFilamentUser()
        ->get(OrganisationUserResource::getUrl())
        ->assertSuccessful();
});

it('can see the records', function (): void {
    $organisation = OrganisationTestHelper::create();
    $users = User::factory()
        ->hasAttached($organisation)
        ->count(3)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(ListOrganisationUsers::class)
        ->assertCanSeeTableRecords($users);
});

it('the role is correctly formatted', function (): void {
    $role = fake()->randomElement(Role::cases());

    $organisation = OrganisationTestHelper::create();
    User::factory()
        ->hasAttached($organisation)
        ->hasOrganisationRole($role, $organisation)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(ListOrganisationUsers::class)
        ->assertSee(__(sprintf('role.%s', $role->value)));
});
