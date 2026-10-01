<?php

declare(strict_types=1);

use App\Enums\Authorization\Permission;
use App\Enums\Authorization\Role;
use App\Filament\Resources\OrganisationUserResource;
use App\Filament\Resources\OrganisationUserResource\Pages\EditOrganisationUser;
use App\Models\OrganisationUserRole;
use App\Models\User;
use Tests\Helpers\Model\OrganisationTestHelper;
use Tests\Helpers\Model\UserTestHelper;

it('does not load the edit page for a user from another organisation', function (): void {
    $organisation = OrganisationTestHelper::create();
    $filamentUser = UserTestHelper::createForOrganisation($organisation);

    $otherOrganisation = OrganisationTestHelper::create();
    $user = User::factory()
        ->hasAttached($otherOrganisation)
        ->create();

    $this->withPermissions($filamentUser, [Permission::USER_ROLE_ORGANISATION_MANAGE])
        ->withFilamentSession($filamentUser, $organisation)
        ->get(OrganisationUserResource::getUrl('edit', ['record' => $user]))
        ->assertNotFound();
});

it('loads the edit page with cpo-manage permission', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisation($organisation);

    $filamentUser = UserTestHelper::createForOrganisation($organisation);
    $permissions = [
        Permission::USER_CREATE,
        Permission::USER_UPDATE,
        Permission::USER_VIEW,
        Permission::USER_ROLE_GLOBAL_MANAGE,
        Permission::USER_ROLE_ORGANISATION_MANAGE,
        Permission::USER_ROLE_ORGANISATION_CPO_MANAGE,
    ];

    $this->withPermissions($filamentUser, $permissions)
        ->withFilamentSession($filamentUser, $organisation)
        ->get(OrganisationUserResource::getUrl('edit', ['record' => $user]))
        ->assertSuccessful();
});

it('loads the edit page without cpo-manage permission', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisation($organisation);

    $filamentUser = UserTestHelper::createForOrganisation($organisation);
    $permissions = [
        Permission::USER_CREATE,
        Permission::USER_UPDATE,
        Permission::USER_VIEW,
        Permission::USER_ROLE_GLOBAL_MANAGE,
        Permission::USER_ROLE_ORGANISATION_MANAGE,
    ];

    $this->withPermissions($filamentUser, $permissions)
        ->withFilamentSession($filamentUser, $organisation)
        ->get(OrganisationUserResource::getUrl('edit', ['record' => $user]))
        ->assertSuccessful();
});

it('can edit a role for a user that is already linked to the organisation', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisation($organisation);

    $role = fake()->randomElement([
        Role::COUNSELOR,
        Role::DATA_PROTECTION_OFFICIAL,
        Role::INPUT_PROCESSOR,
        Role::MANDATE_HOLDER,
        Role::PRIVACY_OFFICER,
    ]);

    $this->assertDatabaseMissing(OrganisationUserRole::class, [
        'role' => $role->value,
        'user_id' => $user->id,
        'organisation_id' => $organisation->id,
    ]);

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(EditOrganisationUser::class, ['record' => $user->id])
        ->fillForm([
            $role->value => true,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $user->refresh();
    $organisationRoles = $user->organisationRoles;
    expect($organisationRoles->count())
        ->toBe(1)
        ->and($organisationRoles->first()->organisation_id)
        ->toBe($organisation->id)
        ->and($organisationRoles->first()->role)
        ->toBe($role);
});

it('can edit a role without assigning any roles', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisation($organisation);

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(EditOrganisationUser::class, ['record' => $user->id])
        ->call('save')
        ->assertHasNoFormErrors();

    $user->refresh();
    $organisationRoles = $user->organisationRoles;
    expect($organisationRoles->count())
        ->toBe(0);
});

it('can assign inputProcessor without cpoManage permissions', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisationWithPermissions($organisation, [
        Permission::USER_ROLE_ORGANISATION_MANAGE,
    ]);

    $userToEdit = UserTestHelper::createForOrganisation($organisation);

    $role = Role::INPUT_PROCESSOR;

    $this->assertDatabaseMissing(OrganisationUserRole::class, [
        'role' => $role->value,
        'user_id' => $userToEdit->id,
        'organisation_id' => $organisation->id,
    ]);

    $this->withFilamentSession($user, $organisation)
        ->createLivewireTestable(EditOrganisationUser::class, ['record' => $userToEdit->id])
        ->fillForm([
            $role->value => true,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $userToEdit->refresh();
    $organisationRoles = $userToEdit->organisationRoles;
    expect($organisationRoles->count())
        ->toBe(1);
});

it('can not assign mandateHolder without cpoManage permissions', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisationWithPermissions($organisation, [
        Permission::USER_ROLE_ORGANISATION_MANAGE,
    ]);

    $userToEdit = UserTestHelper::createForOrganisation($organisation);

    $role = Role::MANDATE_HOLDER;

    $this->assertDatabaseMissing(OrganisationUserRole::class, [
        'role' => $role->value,
        'user_id' => $userToEdit->id,
        'organisation_id' => $organisation->id,
    ]);

    $this->withFilamentSession($user, $organisation)
        ->createLivewireTestable(EditOrganisationUser::class, ['record' => $userToEdit->id])
        ->fillForm([
            $role->value => true,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $userToEdit->refresh();
    $organisationRoles = $userToEdit->organisationRoles;
    expect($organisationRoles->count())
        ->toBe(0);
});

it('shows the mandate-holder-manager toggle with cpo-manage permission', function (): void {
    $organisation = OrganisationTestHelper::create();
    $userToEdit = UserTestHelper::createForOrganisation($organisation);

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(EditOrganisationUser::class, ['record' => $userToEdit->id])
        ->assertFormFieldIsVisible(Role::MANDATE_HOLDER_MANAGER->value);
});

it('only saves mandate-holder-manager together with privacy officer', function (bool $isPrivacyOfficer): void {
    $organisation = OrganisationTestHelper::create();
    $userToEdit = UserTestHelper::createForOrganisation($organisation);
    $userToEdit->assignOrganisationRole(Role::PRIVACY_OFFICER, $organisation);

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(EditOrganisationUser::class, ['record' => $userToEdit->id])
        ->fillForm([
            Role::PRIVACY_OFFICER->value => $isPrivacyOfficer,
            Role::MANDATE_HOLDER_MANAGER->value => true,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($userToEdit->organisationRoles()->where('role', Role::MANDATE_HOLDER_MANAGER->value)->exists())
        ->toBe($isPrivacyOfficer);
})->with([
    [true],
    [false],
]);

it('removes mandate-holder-manager along with privacy officer without cpo-manage permission', function (bool $isPrivacyOfficer): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisationWithPermissions($organisation, [
        Permission::USER_ROLE_ORGANISATION_MANAGE,
    ]);
    $userToEdit = UserTestHelper::createForOrganisation($organisation);
    $userToEdit->assignOrganisationRole(Role::PRIVACY_OFFICER, $organisation);
    $userToEdit->assignOrganisationRole(Role::MANDATE_HOLDER_MANAGER, $organisation);

    $this->withFilamentSession($user, $organisation)
        ->createLivewireTestable(EditOrganisationUser::class, ['record' => $userToEdit->id])
        ->fillForm([
            Role::PRIVACY_OFFICER->value => $isPrivacyOfficer,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($userToEdit->organisationRoles()->where('role', Role::MANDATE_HOLDER_MANAGER->value)->exists())
        ->toBe($isPrivacyOfficer);
})->with([
    [true],
    [false],
]);

it('can not assign mandate-holder-manager without cpo-manage permission', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisationWithPermissions($organisation, [
        Permission::USER_ROLE_ORGANISATION_MANAGE,
    ]);
    $userToEdit = UserTestHelper::createForOrganisation($organisation);
    $userToEdit->assignOrganisationRole(Role::PRIVACY_OFFICER, $organisation);

    $this->withFilamentSession($user, $organisation)
        ->createLivewireTestable(EditOrganisationUser::class, ['record' => $userToEdit->id])
        ->fillForm([
            Role::MANDATE_HOLDER_MANAGER->value => true,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($userToEdit->organisationRoles()->where('role', Role::MANDATE_HOLDER_MANAGER->value)->exists())
        ->toBeFalse();
});

it('can assign mandate-holder but not chief-privacy-officer with mandate-holder-manage permission', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisationWithPermissions($organisation, [
        Permission::USER_ROLE_ORGANISATION_MANAGE,
        Permission::USER_ROLE_ORGANISATION_MANDATE_HOLDER_MANAGE,
    ]);
    $userToEdit = UserTestHelper::createForOrganisation($organisation);

    $this->withFilamentSession($user, $organisation)
        ->createLivewireTestable(EditOrganisationUser::class, ['record' => $userToEdit->id])
        ->fillForm([
            Role::MANDATE_HOLDER->value => true,
            Role::CHIEF_PRIVACY_OFFICER->value => true,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($userToEdit->organisationRoles()->pluck('role')->all())
        ->toBe([Role::MANDATE_HOLDER]);
});

it('hides the mandate-holder toggle on the own account with mandate-holder-manage permission', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisationWithPermissions($organisation, [
        Permission::USER_ROLE_ORGANISATION_MANAGE,
        Permission::USER_ROLE_ORGANISATION_MANDATE_HOLDER_MANAGE,
    ]);

    $this->withFilamentSession($user, $organisation)
        ->createLivewireTestable(EditOrganisationUser::class, ['record' => $user->id])
        ->assertFormFieldDoesNotExist(Role::MANDATE_HOLDER->value);
});

it('shows the mandate-holder toggle on the own account with cpo-manage permission', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisationWithPermissions($organisation, [
        Permission::USER_ROLE_ORGANISATION_MANAGE,
        Permission::USER_ROLE_ORGANISATION_CPO_MANAGE,
    ]);

    $this->withFilamentSession($user, $organisation)
        ->createLivewireTestable(EditOrganisationUser::class, ['record' => $user->id])
        ->assertFormFieldIsVisible(Role::MANDATE_HOLDER->value);
});

it('can not assign mandate-holder to the own account with mandate-holder-manage permission', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisationWithPermissions($organisation, [
        Permission::USER_ROLE_ORGANISATION_MANAGE,
        Permission::USER_ROLE_ORGANISATION_MANDATE_HOLDER_MANAGE,
    ]);
    $user->assignOrganisationRole(Role::PRIVACY_OFFICER, $organisation);

    $this->withFilamentSession($user, $organisation)
        ->createLivewireTestable(EditOrganisationUser::class, ['record' => $user->id])
        ->set(sprintf('data.%s', Role::MANDATE_HOLDER->value), true)
        ->call('save')
        ->assertHasNoFormErrors();

    expect($user->organisationRoles()->pluck('role')->all())
        ->toBe([Role::PRIVACY_OFFICER]);
});

it('keeps mandate-holder when saving the own account with mandate-holder-manage permission', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisationWithPermissions($organisation, [
        Permission::USER_ROLE_ORGANISATION_MANAGE,
        Permission::USER_ROLE_ORGANISATION_MANDATE_HOLDER_MANAGE,
    ]);
    $user->assignOrganisationRole(Role::MANDATE_HOLDER, $organisation);

    $this->withFilamentSession($user, $organisation)
        ->createLivewireTestable(EditOrganisationUser::class, ['record' => $user->id])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($user->organisationRoles()->where('role', Role::MANDATE_HOLDER->value)->exists())
        ->toBeTrue();
});

it('can detach a user that is linked to an organisation', function (): void {
    $organisation = OrganisationTestHelper::create();
    $role = fake()->randomElement([
        Role::INPUT_PROCESSOR,
        Role::PRIVACY_OFFICER,
        Role::COUNSELOR,
        Role::DATA_PROTECTION_OFFICIAL,
        Role::MANDATE_HOLDER,
    ]);

    $user = User::factory()
        ->hasAttached($organisation)
        ->hasOrganisationRole($role, $organisation)
        ->create();

    expect($user->organisations->count())
        ->toBe(1);

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(EditOrganisationUser::class, ['record' => $user->id])
        ->callAction('detach')
        ->assertHasNoFormErrors();

    $user->refresh();
    expect($user->organisations->count())
        ->toBe(0);
});
